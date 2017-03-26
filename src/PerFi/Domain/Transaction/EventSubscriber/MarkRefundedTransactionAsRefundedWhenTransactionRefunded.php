<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\EventSubscriber;

use PerFi\Domain\Transaction\Event\TransactionRefunded;
use PerFi\Domain\Transaction\TransactionRepository;

class MarkRefundedTransactionAsRefundedWhenTransactionRefunded
{
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    /**
     *
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Handle the transaction refunded event
     *
     * Mark the transaction that was refunded as refunded.
     *
     * @param TransactionRefunded $event
     */
    public function __invoke(TransactionRefunded $event)
    {
        $refundedTransaction = $event->refundedTransaction();

        $refundedTransaction->markAsRefunded();

        $this->transactionRepository->save($refundedTransaction);
    }
}
