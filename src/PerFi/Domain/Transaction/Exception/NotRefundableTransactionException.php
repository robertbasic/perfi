<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Exception;

use PerFi\Domain\Transaction\TransactionId;

class NotRefundableTransactionException extends \DomainException
{
    public static function withTransactionId(TransactionId $transactionId) : self
    {
        $message = sprintf("The transaction cannot be refunded, transaction ID: %s", $transactionId);

        return new self($message);
    }
}
