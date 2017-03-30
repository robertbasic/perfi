<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Transaction\EventSubscriber\CreditAssetAccountWhenPaymentMade;
use PerFi\Domain\Transaction\Event\PaymentMade;
use PerFi\Domain\Transaction\Transaction;

class CreditAssetAccountWhenPaymentMadeTest extends TestCase
{
    use TransactionTrait;

    /**
     * @test
     */
    public function asset_account_is_credited()
    {
        $transaction = $this->payTransaction();

        $asset = $transaction->sourceAccount();

        $event = new PaymentMade($transaction);

        $eventSubscriber = new CreditAssetAccountWhenPaymentMade();
        $eventSubscriber->__invoke($event);

        $balances = $asset->balances();

        self::assertNotEmpty($balances);
    }
}
