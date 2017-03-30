<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Transaction\EventSubscriber\DebitAssetAccountWhenTransactionRefunded;
use PerFi\Domain\Transaction\Event\TransactionRefunded;

class DebitAssetAccountWhenTransactionRefundedTest extends TestCase
{
    use TransactionTrait;

    /**
     * @test
     */
    public function asset_account_is_debited()
    {
        $refundedTransaction = $this->payTransaction();

        $transaction = $this->refundTransaction();

        $asset = $transaction->destinationAccount();

        $event = new TransactionRefunded($transaction, $refundedTransaction);

        $eventSubscriber = new DebitAssetAccountWhenTransactionRefunded();
        $eventSubscriber->__invoke($event);

        $balances = $asset->balances();

        self::assertNotEmpty($balances);
    }
}
