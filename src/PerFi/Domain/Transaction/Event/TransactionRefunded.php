<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Event;

use PerFi\Domain\Transaction\Transaction;

class TransactionRefunded
{
    /**
     * @var Transaction
     */
    private $refundTransaction;

    /**
     * @var Transaction
     */
    private $refundedTransaction;

    /**
     * Create a transaction refunded event
     *
     * @param Transaction $refundTransaction
     * @param Transaction $refundedTransaction
     */
    public function __construct(Transaction $refundTransaction, Transaction $refundedTransaction)
    {
        $this->refundTransaction = $refundTransaction;
        $this->refundedTransaction = $refundedTransaction;
    }

    /**
     * The transaction that is the refund
     *
     * @return Transaction
     */
    public function refundTransaction() : Transaction
    {
        return $this->refundTransaction;
    }

    /**
     * The transaction that was refunded
     *
     * @return Transaction
     */
    public function refundedTransaction() : Transaction
    {
        return $this->refundedTransaction;
    }
}
