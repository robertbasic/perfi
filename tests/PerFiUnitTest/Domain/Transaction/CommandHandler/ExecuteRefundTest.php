<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\CommandHandler;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Money\Money;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\AccountTrait;
use PerFiUnitTest\Traits\AmountTrait;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Application\Repository\InMemoryTransactionRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\CommandHandler\ExecuteRefund;
use PerFi\Domain\Transaction\Command\Refund;
use PerFi\Domain\Transaction\Event\TransactionRefunded;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionId;
use PerFi\Domain\Transaction\TransactionRepository;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;

class ExecuteRefundTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use TransactionTrait;

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
     * @var Money
     */
    private $amount;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var Refund
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

        $this->assetAccount = $this->mockAccount('asset');

        $this->expenseAccount = $this->mockAccount('expense');

        $this->amount = $this->amount('500', 'RSD');

        $this->transaction = $this->mockTransaction();
        $this->transaction->shouldReceive('refunded')
            ->andReturn(false)
            ->byDefault();
        $this->transaction->shouldReceive('destinationAccount')
            ->andReturn($this->expenseAccount)
            ->byDefault();
        $this->transaction->shouldReceive('sourceAccount')
            ->andReturn($this->assetAccount)
            ->byDefault();
        $this->transaction->shouldReceive('amount')
            ->andReturn($this->amount)
            ->byDefault();
        $this->transaction->shouldReceive('description')
            ->andReturn('groceries')
            ->byDefault();

        $this->command = new Refund($this->transaction);

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

    /**
     * @test
     * @expectedException PerFi\Domain\Transaction\Exception\TransactionNotRefundableException
     * @expectedExceptionMessage A refund transaction between Cash, asset and Groceries, expense accounts is not refundable
     */
    public function when_invoked_with_not_refundable_transaction_throws_exception()
    {
        $this->transaction->shouldReceive('destinationAccount')
            ->once()
            ->andReturn($this->assetAccount);
        $this->transaction->shouldReceive('sourceAccount')
            ->once()
            ->andReturn($this->expenseAccount);

        $command = new Refund($this->transaction);

        $this->commandHandler->__invoke($command);
    }

    /**
     * @test
     * @expectedException PerFi\Domain\Transaction\Exception\TransactionAlreadyRefundedException
     * @expectedExceptionMessageRegEx #The pay transaction between Cash, asset and Groceries, expense accounts is already refunded. ID: .*#
     */
    public function when_refunding_an_already_refunded_transaction_throws_exception()
    {
        $transactionType = $this->pay();

        $this->transaction->shouldReceive('refunded')
            ->once()
            ->andReturn(true);
        $this->transaction->shouldReceive('type')
            ->once()
            ->andReturn($transactionType);
        $this->transaction->shouldReceive('id')
            ->once()
            ->andReturn(TransactionId::fromString('fddf4716-6c0e-4f54-b539-d2d480a50d1a'));

        $command = new Refund($this->transaction);

        $this->commandHandler->__invoke($command);
    }
}
