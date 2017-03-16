<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Exception;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\TransactionType;

class NotExecutableTransactionException extends \DomainException
{
    public static function withTypeAndAccounts(
        TransactionType $type,
        Account $sourceAccount,
        Account $destinationAccount
    ) : self
    {
        $message = sprintf("The %s transaction cannot be executed between %s and %s accounts", $type, $sourceAccount, $destinationAccount);
        return new self($message);
    }
}
