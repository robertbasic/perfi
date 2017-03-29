<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Command;

use Money\Money;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionType;

class Refund
{
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var TransactionType
     */
    private $transactionType;

    /**
     * @var Account
     */
    private $sourceAccount;

    /**
     * @var Account
     */
    private $destinationAccount;

    /**
     * @var Money
     */
    private $amount;

    /**
     * @var string
     */
    private $description;

    /**
     * @var TransactionDate
     */
    private $date;

    /**
     * Create a refund transaction command
     *
     * The source of a transaction that is refunded becomes the new destination
     * account, and the destination becomes the new source account.
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->transactionType = TransactionType::fromString('refund');
        $this->sourceAccount = $transaction->destinationAccount();
        $this->destinationAccount = $transaction->sourceAccount();
        $this->amount = $transaction->amount();
        $this->description = "Refund " . $transaction->description();
        $this->date = TransactionDate::fromString('now');
    }

    /**
     * Get the Transaction that is to be refunded
     *
     * @return Transaction
     */
    public function transaction() : Transaction
    {
        return $this->transaction;
    }

    /**
     * Get the type of the transaction
     *
     * @return TransactionType
     */
    public function transactionType() : TransactionType
    {
        return $this->transactionType;
    }

    /**
     * Get the source account for the transaction
     *
     * @return Account
     */
    public function sourceAccount() : Account
    {
        return $this->sourceAccount;
    }

    /**
     * Get the destination account for the transaction
     *
     * @return Account
     */
    public function destinationAccount() : Account
    {
        return $this->destinationAccount;
    }

    /**
     * Get the amount for the transaction
     *
     * @return Money
     */
    public function amount() : Money
    {
        return $this->amount;
    }

    /**
     * Get the description for the transaction
     *
     * @return string
     */
    public function description() : string
    {
        return $this->description;
    }

    /**
     * Get the transaction date
     *
     * @return TransactionDate
     */
    public function date() : TransactionDate
    {
        return $this->date;
    }
}
