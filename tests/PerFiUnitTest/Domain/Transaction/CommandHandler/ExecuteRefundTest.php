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
use PerFi\Domain\Transaction\CommandHandler\ExecuteRefund;
use PerFi\Domain\Transaction\Command\Pay;
use PerFi\Domain\Transaction\Command\Refund;
use PerFi\Domain\Transaction\Event\TransactionRefunded;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionRepository;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;

class ExecuteRefundTest extends TestCase
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
     * @var ExecuteRefund
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

        $amount = MoneyFactory::amountInCurrency('500', 'RSD');

        $transaction = m::mock(Transaction::class);
        $transaction->shouldReceive('canBeRefunded')
            ->andReturn(true)
            ->byDefault();
        $transaction->shouldReceive('destinationAccount')
            ->andReturn($this->expenseAccount)
            ->byDefault();
        $transaction->shouldReceive('sourceAccount')
            ->andReturn($this->assetAccount)
            ->byDefault();
        $transaction->shouldReceive('amount')
            ->andReturn($amount);
        $transaction->shouldReceive('description')
            ->andReturn('groceries')
            ->byDefault();

        $this->command = new Refund($transaction);

        $this->commandHandler = new ExecuteRefund(
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
            ->with(m::type(TransactionRefunded::class));

        $this->commandHandler->__invoke($this->command);
    }
}
