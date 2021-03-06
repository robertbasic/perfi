<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class PayTransaction
{
    /**
     * Check is transaction of pay type
     *
     * @param Transaction $transaction
     * @return bool
     */
    public function isSatisfiedBy(Transaction $transaction) : bool
    {
        return (string) $transaction->type() === TransactionType::TRANSACTION_TYPE_PAY;
    }
}
