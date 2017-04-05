<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\EventSubscriber;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\EventBusTrait;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Account\EventSubscriber\DebitAssetAccountWhenTransactionRefunded;
use PerFi\Domain\Account\Event\AccountBalanceChanged;
use PerFi\Domain\Transaction\Event\TransactionRefunded;
use PerFi\Domain\Transaction\Transaction;
use SimpleBus\Message\Bus\MessageBus;

class DebitAssetAccountWhenTransactionRefundedTest extends TestCase
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
     * @var DebitAssetAccountWhenTransactionRefunded
     */
    private $eventSubscriber;

    public function setup()
    {
        $this->eventBus = $this->mockEventBus();

        $this->refundedTransaction = $this->payTransaction();

        $this->transaction = $this->refundTransaction();

        $this->event = new TransactionRefunded($this->transaction, $this->refundedTransaction);

        $this->eventSubscriber = new DebitAssetAccountWhenTransactionRefunded($this->eventBus);
    }

    /**
     * @test
     */
    public function asset_account_is_debited()
    {
        $asset = $this->transaction->destinationAccount();

        $event = new TransactionRefunded($this->transaction, $this->refundedTransaction);

        $this->eventSubscriber->__invoke($this->event);

        $balances = $asset->balances();

        self::assertNotEmpty($balances);
    }

    /**
     * @test
     */
    public function destination_account_debited_event_is_handled_when_asset_account_is_debited()
    {
        $this->eventBus->shouldReceive('handle')
            ->once()
            ->with(m::type(AccountBalanceChanged::class));

        $this->eventSubscriber->__invoke($this->event);
    }}
