<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\EventSubscriber;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\EventBusTrait;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Account\Event\DestinationAccountDebited;
use PerFi\Domain\Account\EventSubscriber\DebitExpenseAccountWhenPaymentMade;
use PerFi\Domain\Transaction\Event\PaymentMade;
use SimpleBus\Message\Bus\MessageBus;

class DebitExpenseAccountWhenPaymentMadeTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use TransactionTrait;
    use EventBusTrait;

    /**
     * @var MessageBus
     */
    private $eventBus;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var PaymentMade
     */
    private $event;

    /**
     * @var DebitExpenseAccountWhenPaymentMade
     */
    private $eventSubscriber;

    public function setup()
    {
        $this->eventBus = $this->mockEventBus();

        $this->transaction = $this->payTransaction();

        $this->event = new PaymentMade($this->transaction);

        $this->eventSubscriber = new DebitExpenseAccountWhenPaymentMade($this->eventBus);
    }

    /**
     * @test
     */
    public function expense_account_is_debited()
    {
        $expense = $this->transaction->destinationAccount();

        $this->eventSubscriber->__invoke($this->event);

        $balances = $expense->balances();

        self::assertNotEmpty($balances);
    }

    /**
     * @test
     */
    public function source_account_credited_event_is_handled_when_destination_account_is_debited()
    {
        $this->eventBus->shouldReceive('handle')
            ->once()
            ->with(m::type(DestinationAccountDebited::class));

        $this->eventSubscriber->__invoke($this->event);
    }
}
