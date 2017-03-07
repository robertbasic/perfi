<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\CommandHandler;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Money\Money;
use PHPUnit\Framework\TestCase;
use PerFi\Application\Transaction\InMemoryTransactionRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Command;
use PerFi\Domain\CommandHandler;
use PerFi\Domain\Event;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\CommandHandler\ExecuteTransaction as ExecuteTransactionHandler;
use PerFi\Domain\Transaction\Command\ExecuteTransaction as ExecuteTransactionCommand;
use PerFi\Domain\Transaction\TransactionRepository;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;

class ExecuteTransactionTest extends TestCase
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
    private $sourceAccount;

    /**
     * @var Account
     */
    private $destinationAccount;

    /**
     * @var Money
     */
    private $amount;

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

        $this->eventBus = m::mock(MessageBusSupportingMiddleware::class);
        $this->eventBus->shouldReceive('handle')
            ->byDefault();

        $this->sourceAccount = m::mock(Account::class);

        $this->destinationAccount = m::mock(Account::class);

        $this->amount = MoneyFactory::amountInCurrency('500', 'RSD');

        $this->command = new ExecuteTransactionCommand(
            $this->sourceAccount,
            $this->destinationAccount,
            $this->amount,
            'supermarket'
        );

        $this->commandHandler = new ExecuteTransactionHandler(
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
            ->with(m::type(Event::class));

        $this->commandHandler->__invoke($this->command);
    }
}
