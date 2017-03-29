<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Exception;

use PerFi\Domain\Transaction\Transaction;

class TransactionAlreadyRefundedException extends \DomainException
{
    public static function withTransaction(Transaction $transaction) : self
    {
        $message = sprintf("The %s transaction between %s and %s accounts is already refunded. ID: %s", $transaction->type(), $transaction->sourceAccount(), $transaction->destinationAccount(), $transaction->id());
        return new self($message);
    }
}
