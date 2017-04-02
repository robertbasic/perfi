<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\CommandHandler;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\AccountTrait;
use PerFiUnitTest\Traits\EventBusTrait;
use PerFi\Application\Repository\InMemoryTransactionRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\CommandHandler\ExecutePayment;
use PerFi\Domain\Transaction\Command\Pay;
use PerFi\Domain\Transaction\Event\PaymentMade;
use PerFi\Domain\Transaction\TransactionRepository;
use SimpleBus\Message\Bus\MessageBus;

class ExecutePaymentTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use AccountTrait;
    use EventBusTrait;

    /**
     * @var TransactionRepository
     */
    private $repository;

    /**
     * @var MessageBus
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
    * @var string
    */
    private $date;

    /**
    * @var string
    */
    private $description;

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

        $this->eventBus = $this->mockEventBus();

        $this->assetAccount = $this->mockAccount('asset');

        $this->expenseAccount = $this->mockAccount('expense');

        $this->amount = '500';
        $this->currency = 'RSD';
        $this->date = '2017-03-12';
        $this->description = 'supermarket';

        $this->command = new Pay(
            $this->assetAccount,
            $this->expenseAccount,
            $this->amount,
            $this->currency,
            $this->date,
            $this->description
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

    /**
     * @test
     * @expectedException PerFi\Domain\Transaction\Exception\TransactionNotPayableException
     * @expectedExceptionMessage A pay transaction between Groceries, expense and Cash, asset accounts is not payable
     */
    public function when_invoked_with_not_payable_transaction_throws_exception()
    {
        $command = new Pay(
            $this->expenseAccount,
            $this->assetAccount,
            $this->amount,
            $this->currency,
            $this->date,
            $this->description
        );

        $this->commandHandler->__invoke($command);
    }
}
