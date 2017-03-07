<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

use Money\Money;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionId;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Transaction
{
    /**
     * @var TransactionId
     */
    private $id;

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
     * @var TransactionDate
     */
    private $date;

    /**
     * @var string
     */
    private $description;

    /**
     * Create a transaction
     *
     * @param TransactionId $id
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     * @param Money $amount
     * @param TransactionDate $date
     * @param string $description
     */
    private function __construct(
        TransactionId $id,
        Account $sourceAccount,
        Account $destinationAccount,
        Money $amount,
        TransactionDate $date,
        string $description
    )
    {
        $this->id = $id;
        $this->sourceAccount = $sourceAccount;
        $this->destinationAccount = $destinationAccount;
        $this->amount = $amount;
        $this->date = $date;
        $this->description = $description;
    }

    /**
     * Create a transaction between a source and a destination account
     *
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     * @param Money $amount
     * @param string $description
     * @return Transaction
     */
    public static function betweenAccounts(
        Account $sourceAccount,
        Account $destinationAccount,
        Money $amount,
        string $description
    ) : self
    {
        Assert::stringNotEmpty($description);

        $id = TransactionId::fromUuid(Uuid::uuid4());

        $date = TransactionDate::now();

        return new self(
            $id,
            $sourceAccount,
            $destinationAccount,
            $amount,
            $date,
            $description
        );
    }

    /**
     * Credit the source account for the transaction amount
     */
    public function creditSourceAccount()
    {
        $this->sourceAccount->credit($this->amount);
    }

    /**
     * Debit the destination account for the transaction amount
     */
    public function debitDestinationAccount()
    {
        $this->destinationAccount->debit($this->amount);
    }

    /**
     * Get the ID of the transaction
     *
     * @return TransactionId
     */
    public function id() : TransactionId
    {
        return $this->id;
    }

    /**
     * Get the source account of the transaction
     *
     * @return Account
     */
    public function sourceAccount() : Account
    {
        return $this->sourceAccount;
    }

    /**
     * Get the destination account of the transaction
     *
     * @return Account
     */
    public function destinationAccount() : Account
    {
        return $this->destinationAccount;
    }

    /**
     * Get the transaction amount
     *
     * @return Money
     */
    public function amount() : Money
    {
        return $this->amount;
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

    /**
     * Get the transaction description
     *
     * @return string
     */
    public function description() : string
    {
        return $this->description;
    }
}
