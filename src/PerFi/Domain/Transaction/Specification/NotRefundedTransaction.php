<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Transaction\Transaction;

class NotRefundedTransaction
{
    /**
     * Check that the transaction is not refunded
     *
     * @param Transaction $transaction
     * @return bool
     */
    public function isSatisfiedBy(Transaction $transaction) : bool
    {
        return !$transaction->refunded();
    }
}
