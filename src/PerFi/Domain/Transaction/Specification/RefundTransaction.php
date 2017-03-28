<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class RefundTransaction
{
    /**
     * Check is transaction of refund type
     *
     * @param Transaction $transaction
     * @return bool
     */
    public function isSatisfiedBy(Transaction $transaction) : bool
    {
        return (string) $transaction->type() === TransactionType::TRANSACTION_TYPE_REFUND;
    }
}
