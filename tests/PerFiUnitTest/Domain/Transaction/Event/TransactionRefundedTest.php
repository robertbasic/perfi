<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Event;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Transaction\Event\TransactionRefunded;

class TransactionRefundedTest extends TestCase
{
    use TransactionTrait;

    /**
     * @test
     */
    public function transaction_is_set_on_event()
    {
        $refundedTransaction = $this->payTransaction();

        $transaction = $this->refundTransaction();

        $event = new TransactionRefunded($transaction, $refundedTransaction);

        $result = $event->refundTransaction();
        $refundedTransactionResult = $event->refundedTransaction();

        self::assertSame($transaction, $result);
        self::assertSame($refundedTransaction, $refundedTransactionResult);
    }
}
