<?php
declare(strict_types=1);

namespace PerFiFeatureTest\Domain;

use Behat\Behat\Context\Context;
use PerFi\Application\Transaction\InMemoryTransactionRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\CommandHandler\ExecutePayment;
use PerFi\Domain\Transaction\CommandHandler\ExecuteRefund;
use PerFi\Domain\Transaction\Command\Pay;
use PerFi\Domain\Transaction\Command\Refund;
use PerFi\Domain\Transaction\EventSubscriber\CreditAssetAccountWhenPaymentMade;
use PerFi\Domain\Transaction\EventSubscriber\DebitExpenseAccountWhenPaymentMade;
use PerFi\Domain\Transaction\Event\PaymentMade;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionRepository;
use PerFi\Domain\Transaction\TransactionType;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleBus\Message\Subscriber\Resolver\NameBasedMessageSubscriberResolver;
use Webmozart\Assert\Assert;

class TransactionContext implements Context
{

    /**
     * @var array
     */
    private $accounts;

    /**
     * @var Pay|Refund
     */
    private $command;

    /**
     * @var ExecutePayment
     */
    private $payCommandHandler;

    /**
     * @var ExecuteRefund
     */
    private $refundCommandHandler;

    /**
     * @var TransactionRepository
     */
    private $repository;

    /**
     * @var MessageBus
     */
    private $eventBus;

    /** @BeforeScenario */
    public function setup()
    {
        $this->accounts = [];
        $this->repository = new InMemoryTransactionRepository();
        $this->eventBus = $this->getEventBus();
        $this->payCommandHandler = new ExecutePayment(
            $this->repository,
            $this->eventBus
        );
        $this->refundCommandHandler = new ExecuteRefund(
            $this->repository,
            $this->eventBus
        );
    }

    /**
     * @Given I have an :type account called :title
     */
    public function iHaveAnAccountOfCertianTypeCalled($type, $title)
    {
        $hash = $this->hashAccountTitle($title);
        $type = AccountType::fromString($type);
        $this->accounts[$hash] = Account::byTypeWithTitle($type, $title);
    }

    /**
     * @When :amount :currency is payed for :description from :source to :destination on :date
     */
    public function anAmountInCurrencyIsPayedForSomething($amount, $currency, $description, $source, $destination, $date)
    {
        $sourceAccount = $this->getAccountByTitle($source);
        $destinationAccount = $this->getAccountByTitle($destination);

        $this->command = new Pay(
            $sourceAccount,
            $destinationAccount,
            $amount,
            $currency,
            $date,
            $description
        );

        $this->payCommandHandler->__invoke($this->command);
    }

    /**
     * @When I refund :amount :currency from the :source to :destination on :date
     */
    public function iRefundAmountInCurrency($amount, $currency, $source, $destination, $date)
    {
        // Source and destination intentionally switched
        // because we're first creating a "pay" transaction
        // which will be refunded
        $sourceAccount = $this->getAccountByTitle($destination);
        $destinationAccount = $this->getAccountByTitle($source);
        $description = "supermarket";
        $amount = MoneyFactory::amountInCurrency($amount, $currency);
        $date = TransactionDate::fromString('2017-03-12');

        $transaction = Transaction::betweenAccounts(
            TransactionType::fromString('pay'),
            $sourceAccount,
            $destinationAccount,
            $amount,
            $date,
            $description
        );

        $this->command = new Refund($transaction);

        $this->refundCommandHandler->__invoke($this->command);
    }

    /**
     * @Then I should have :amount :currency funds less in :title :type account
     */
    public function iShouldHaveLessFundsInSourceAccount($amount, $currency, $title, $type)
    {
        $expected = MoneyFactory::amountInCurrency('-' . $amount, $currency);

        $sourceAccount = $this->command->sourceAccount();

        $balances = $sourceAccount->balances();

        foreach ($balances as $result) {
            if ((string) $result->getCurrency() === $currency) {
                Assert::same($result->getAmount(), $expected->getAmount());
                Assert::same((string) $result->getCurrency(), (string) $expected->getCurrency());
            }
        }
    }

    /**
     * @Then I should have :amount :currency funds more in :title :type account
     */
    public function iShouldHaveMoreFundsInDestinationAccount($amount, $currency, $title, $type)
    {
        $expected = MoneyFactory::amountInCurrency($amount, $currency);

        $destinationAccount = $this->command->destinationAccount();

        $balances = $destinationAccount->balances();

        foreach ($balances as $result) {
            if ((string) $result->getCurrency() === $currency) {
                Assert::same($result->getAmount(), $expected->getAmount());
                Assert::same((string) $result->getCurrency(), (string) $expected->getCurrency());
            }
        }
    }

    /**
     * @Then the transaction should have happened on :date
     */
    public function theTransactionShouldHaveHappenedOnDate($date)
    {
        $expected = TransactionDate::fromString($date);

        $transactions = $this->repository->getAll();

        foreach ($transactions as $transaction) {
            Assert::same((string) $transaction->date(), (string) $expected);
        }
    }

    private function getAccountByTitle($title)
    {
        $hash = $this->hashAccountTitle($title);
        return $this->accounts[$hash];
    }

    private function hashAccountTitle($title)
    {
        return trim(strtolower($title));
    }

    private function getEventBus()
    {
        $eventSubscribersByEventName = [
            PaymentMade::class => [
                CreditAssetAccountWhenPaymentMade::class,
                DebitExpenseAccountWhenPaymentMade::class,
            ]
        ];

        $serviceLocator = function ($serviceId) {
            return new $serviceId();
        };

        $eventSubscriberCollection = new CallableCollection(
            $eventSubscribersByEventName,
            new ServiceLocatorAwareCallableResolver($serviceLocator)
        );
        $eventNameResolver = new ClassBasedNameResolver();
        $eventSubscribersResolver = new NameBasedMessageSubscriberResolver(
            $eventNameResolver,
            $eventSubscriberCollection
        );

        $eventBus = new MessageBusSupportingMiddleware();
        $eventBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
        $eventBus->appendMiddleware(
            new NotifiesMessageSubscribersMiddleware(
                $eventSubscribersResolver
            )
        );

        return $eventBus;
    }
}
