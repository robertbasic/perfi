<?php
declare(strict_types=1);

namespace PerFiFeatureTest\Domain;

use Behat\Behat\Context\Context;
use PerFi\Application\Transaction\InMemoryTransactionRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\EventBusFactory;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\CommandHandler\ExecuteTransaction as ExecuteTransactionHandler;
use PerFi\Domain\Transaction\Command\ExecuteTransaction as ExecuteTransactionCommand;
use PerFi\Domain\Transaction\EventSubscriber\CreditSourceAccountWhenTransactionExecuted;
use PerFi\Domain\Transaction\EventSubscriber\DebitDestinationAccountWhenTransactionExecuted;
use PerFi\Domain\Transaction\Event\TransactionExecuted;
use Webmozart\Assert\Assert;

class TransactionContext implements Context
{

    /**
     * @var array
     */
    private $accounts;

    /** @BeforeScenario */
    public function setup()
    {
        $this->accounts = [];
    }

    /**
     * @Given I have an :type account called :title
     */
    public function iHaveAnAccountOfCertianTypeCalled($type, $title)
    {
        $hash = $this->hashAccountTitle($title);
        $this->accounts[$hash] = Account::byStringType($type, $title);
    }

    /**
     * @When :amount :currency is payed for :description from :source to :destination
     */
    public function anAmountInCurrencyIsPayedForSomething($amount, $currency, $description, $source, $destination)
    {
        $repository = new InMemoryTransactionRepository();
        $sourceAccount = $this->getAccountByTitle($source);
        $destinationAccount = $this->getAccountByTitle($destination);
        $amount = MoneyFactory::amountInCurrency($amount, $currency);

        $this->command = new ExecuteTransactionCommand(
            $sourceAccount,
            $destinationAccount,
            $amount,
            $description
        );

        $eventBus = EventBusFactory::getEventBus();

        $commandHandler = new ExecuteTransactionHandler($repository, $eventBus);

        $commandHandler->__invoke($this->command);
    }

    /**
     * @When :amount :currency is charged for :description from :source to :destination
     */
    public function anAmountInCurrencyIsChargedForSomething($amount, $currency, $description, $source, $destination)
    {
        $repository = new InMemoryTransactionRepository();
        $sourceAccount = $this->getAccountByTitle($source);
        $destinationAccount = $this->getAccountByTitle($destination);
        $amount = MoneyFactory::amountInCurrency($amount, $currency);

        $this->command = new ExecuteTransactionCommand(
            $sourceAccount,
            $destinationAccount,
            $amount,
            $description
        );

        $eventBus = EventBusFactory::getEventBus();

        $commandHandler = new ExecuteTransactionHandler($repository, $eventBus);

        $commandHandler->__invoke($this->command);
    }

    /**
     * @Then I should have :amount :currency funds less in :title :type account
     */
    public function iShouldHaveLessFundsInSourceAccount($amount, $currency, $title, $type)
    {
        $expected = MoneyFactory::amountInCurrency('-' . $amount, $currency);

        $transaction = $this->command->payload();
        $sourceAccount = $transaction->sourceAccount();

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

        $transaction = $this->command->payload();
        $destinationAccount = $transaction->destinationAccount();

        $balances = $destinationAccount->balances();

        foreach ($balances as $result) {
            if ((string) $result->getCurrency() === $currency) {
                Assert::same($result->getAmount(), $expected->getAmount());
                Assert::same((string) $result->getCurrency(), (string) $expected->getCurrency());
            }
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
}
