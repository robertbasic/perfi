<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Exception;

use PerFi\Domain\Transaction\Transaction;

class TransactionNotRefundableException extends \DomainException
{
    public static function withTransaction(Transaction $transaction) : self
    {
        $message = sprintf("A %s transaction between %s and %s accounts is not refundable", $transaction->type(), $transaction->sourceAccount(), $transaction->destinationAccount());
        return new self($message);
    }
}
