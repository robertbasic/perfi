<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\CommandHandler;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Money\Money;
use PHPUnit\Framework\TestCase;
use PerFi\Application\Transaction\InMemoryTransactionRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Command;
use PerFi\Domain\CommandHandler;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\CommandHandler\ExecutePayment;
use PerFi\Domain\Transaction\Command\Pay;
use PerFi\Domain\Transaction\Event\PaymentMade;
use PerFi\Domain\Transaction\TransactionRepository;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;

class ExecutePaymentTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var TransactionRepository
     */
    private $repository;

    /**
     * @var MessageBusSupportingMiddleware
     */
    private $eventBus;

    /**
     * @var Account
     */
    private $assetAccount;

    /**
     * @var Account
     */
    private $expenseAccount;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var Pay
     */
    private $command;

    /**
     * @var ExecutePayment
     */
    private $commandHandler;

    public function setup()
    {
        $this->repository = new InMemoryTransactionRepository();

        $this->eventBus = m::mock(MessageBusSupportingMiddleware::class);
        $this->eventBus->shouldReceive('handle')
            ->byDefault();

        $this->assetAccount = m::mock(Account::class);
        $this->assetAccount->shouldReceive('type')
            ->andReturn(AccountType::fromString('asset'))
            ->byDefault();

        $this->expenseAccount = m::mock(Account::class);
        $this->expenseAccount->shouldReceive('type')
            ->andReturn(AccountType::fromString('expense'))
            ->byDefault();

        $this->amount = '500';
        $this->currency = 'RSD';

        $this->command = new Pay(
            $this->assetAccount,
            $this->expenseAccount,
            $this->amount,
            $this->currency,
            '2017-03-12',
            'supermarket'
        );

        $this->commandHandler = new ExecutePayment(
            $this->repository,
            $this->eventBus
        );
    }

    /**
     * @test
     */
    public function when_invoked_adds_transaction_to_repository()
    {
        $this->commandHandler->__invoke($this->command);

        $result = $this->repository->getAll();

        $expected = 1;

        self::assertSame($expected, count($result));
    }

    /**
     * @test
     */
    public function when_invoked_lets_the_event_bus_handle_the_event()
    {
        $this->eventBus->shouldReceive('handle')
            ->once()
            ->with(m::type(PaymentMade::class));

        $this->commandHandler->__invoke($this->command);
    }
}
