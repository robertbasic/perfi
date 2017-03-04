<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\CommandHandler;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Application\Transaction\InMemoryTransactionRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Command;
use PerFi\Domain\CommandHandler;
use PerFi\Domain\Transaction\CommandHandler\ExecuteTransaction as ExecuteTransactionHandler;
use PerFi\Domain\Transaction\Command\ExecuteTransaction as ExecuteTransactionCommand;
use PerFi\Domain\Transaction\TransactionRepository;

class ExecuteTransactionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var TransactionRepository
     */
    private $repository;

    /**
     * @var Account
     */
    private $sourceAccount;

    /**
     * @var Account
     */
    private $destinationAccount;

    /**
     * @var Command
     */
    private $command;

    /**
     * @var CommandHandler
     */
    private $commandHandler;

    public function setup()
    {
        $this->repository = new InMemoryTransactionRepository();

        $this->sourceAccount = m::mock(Account::class);
        $this->sourceAccount->shouldReceive('credit')
            ->byDefault();

        $this->destinationAccount = m::mock(Account::class);
        $this->destinationAccount->shouldReceive('debit')
            ->byDefault();

        $this->command = new ExecuteTransactionCommand(
            $this->sourceAccount,
            $this->destinationAccount,
            '500',
            'RSD',
            'supermarket'
        );

        $this->commandHandler = new ExecuteTransactionHandler(
            $this->repository
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
    public function when_invoked_credits_transaction_amount_on_source_account()
    {
        $this->sourceAccount->shouldReceive('credit')
            ->once()
            ->with($this->command->payload()->amount());

        $this->commandHandler->__invoke($this->command);
    }

    /**
     * @test
     */
    public function when_invoked_debits_transaction_amount_on_destination_account()
    {
        $this->destinationAccount->shouldReceive('debit')
            ->once()
            ->with($this->command->payload()->amount());

        $this->commandHandler->__invoke($this->command);
    }
}
