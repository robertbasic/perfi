<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Transaction\TransactionType;

class RefundTransaction
{
    public function isSatisfiedBy(TransactionType $type)
    {
        return (string) $type === TransactionType::TRANSACTION_TYPE_REFUND;
    }
}
