<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\EventSubscriber;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\EventBusTrait;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Account\EventSubscriber\CreditAssetAccountWhenPaymentMade;
use PerFi\Domain\Account\Event\SourceAccountCredited;
use PerFi\Domain\Transaction\Event\PaymentMade;
use PerFi\Domain\Transaction\Transaction;

class CreditAssetAccountWhenPaymentMadeTest extends TestCase
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
     * @var CreditAssetAccountWhenPaymentMade
     */
    private $eventSubscriber;

    public function setup()
    {
        $this->eventBus = $this->mockEventBus();

        $this->transaction = $this->payTransaction();

        $this->event = new PaymentMade($this->transaction);

        $this->eventSubscriber = new CreditAssetAccountWhenPaymentMade($this->eventBus);
    }

    /**
     * @test
     */
    public function asset_account_is_credited()
    {
        $asset = $this->transaction->sourceAccount();

        $this->eventSubscriber->__invoke($this->event);

        $balances = $asset->balances();

        self::assertNotEmpty($balances);
    }

    /**
     * @test
     */
    public function source_account_credited_event_is_handled_when_asset_account_is_credited()
    {
        $this->eventBus->shouldReceive('handle')
            ->once()
            ->with(m::type(SourceAccountCredited::class));

        $this->eventSubscriber->__invoke($this->event);
    }
}
