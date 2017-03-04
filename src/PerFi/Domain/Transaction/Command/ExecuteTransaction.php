<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Command;

use Money\Money;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Command;
use PerFi\Domain\Transaction\Transaction;

class ExecuteTransaction implements Command
{
    /**
     * @var Transaction
     */
    private $transaction;

    public function __construct(
        Account $sourceAccount,
        Account $destinationAccount,
        Money $amount,
        string $description
    )
    {
        $this->transaction = Transaction::betweenAccounts(
            $sourceAccount,
            $destinationAccount,
            $amount,
            $description
        );
    }

    public function payload() : Transaction
    {
        return $this->transaction;
    }
}
