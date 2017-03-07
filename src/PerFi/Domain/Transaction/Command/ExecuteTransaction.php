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

    /**
     * Execute transaction command
     *
     * Creates a transaction which is to be executed between two accounts.
     * Every transaction needs an amount that is transferred between accounts,
     * and a description of the transaction.
     *
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     * @param Money $amount
     * @param string $description
     */
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

    /**
     * The payload of the command
     *
     * @return Transaction
     */
    public function payload() : Transaction
    {
        return $this->transaction;
    }
}
