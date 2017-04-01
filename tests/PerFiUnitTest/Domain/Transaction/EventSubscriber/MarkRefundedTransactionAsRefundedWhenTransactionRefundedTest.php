<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Application\Repository\InMemoryTransactionRepository;
use PerFi\Domain\Transaction\EventSubscriber\MarkRefundedTransactionAsRefundedWhenTransactionRefunded;
use PerFi\Domain\Transaction\Event\TransactionRefunded;

class MarkRefundedTransactionAsRefundedWhenTransactionRefundedTest extends TestCase
{
    use TransactionTrait;

    /**
     * @test
     */
    public function refunded_transaction_is_marked_as_refunded()
    {
        $transactionRepository = new InMemoryTransactionRepository();

        $refundedTransaction = $this->payTransaction();

        $transaction = $this->refundTransaction();

        $event = new TransactionRefunded($transaction, $refundedTransaction);

        $eventSubscriber = new MarkRefundedTransactionAsRefundedWhenTransactionRefunded($transactionRepository);
        $eventSubscriber->__invoke($event);

        $result = $transactionRepository->get($refundedTransaction->id());

        self::assertTrue($result->refunded());
    }
}
