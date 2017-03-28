<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class RefundTransaction
{
    public function isSatisfiedBy(Transaction $transaction)
    {
        return (string) $transaction->type() === TransactionType::TRANSACTION_TYPE_REFUND;
    }
}
