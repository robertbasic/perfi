<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\EventSubscriber;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\EventBusTrait;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Account\Event\SourceAccountCredited;
use PerFi\Domain\Account\EventSubscriber\CreditExpenseAccountWhenTransactionRefunded;
use PerFi\Domain\Transaction\Event\TransactionRefunded;

class CreditExpenseAccountWhenTransactionRefundedTest extends TestCase
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
    private $refundedTransaction;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var TransactionRefunded
     */
    private $event;

    /**
     * @var CreditExpenseAccountWhenTransactionRefunded
     */
    private $eventSubscriber;

    public function setup()
    {
        $this->eventBus = $this->mockEventBus();

        $this->refundedTransaction = $this->payTransaction();

        $this->transaction = $this->refundTransaction();

        $this->event = new TransactionRefunded($this->transaction, $this->refundedTransaction);

        $this->eventSubscriber = new CreditExpenseAccountWhenTransactionRefunded($this->eventBus);
    }

    /**
     * @test
     */
    public function expense_account_is_credited()
    {
        $expense = $this->transaction->sourceAccount();

        $this->eventSubscriber->__invoke($this->event);

        $balances = $expense->balances();

        self::assertNotEmpty($balances);
    }

    /**
     * @test
     */
    public function source_account_credited_event_is_handled_when_expense_account_is_credited()
    {
        $this->eventBus->shouldReceive('handle')
            ->once()
            ->with(m::type(SourceAccountCredited::class));

        $this->eventSubscriber->__invoke($this->event);
    }
}
