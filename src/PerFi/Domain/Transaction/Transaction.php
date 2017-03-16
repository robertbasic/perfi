<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

use Money\Money;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\Exception\NotExecutableTransactionException;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionId;
use PerFi\Domain\Transaction\TransactionRecordDate;
use PerFi\Domain\Transaction\TransactionType;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Transaction
{
    /**
     * @var TransactionId
     */
    private $id;

    /**
     * @var TransactionType
     */
    private $type;

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
     * @var TransactionRecordDate
     */
    private $recordDate;

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
        TransactionType $type,
        Account $sourceAccount,
        Account $destinationAccount,
        Money $amount,
        TransactionDate $date,
        TransactionRecordDate $recordDate,
        string $description
    )
    {
        if (!$this->canTransactionBeExecuted($type, $sourceAccount, $destinationAccount)) {
            throw NotExecutableTransactionException::withTypeAndAccounts($type, $sourceAccount, $destinationAccount);
        }

        $this->id = $id;
        $this->type = $type;
        $this->sourceAccount = $sourceAccount;
        $this->destinationAccount = $destinationAccount;
        $this->amount = $amount;
        $this->date = $date;
        $this->recordDate = $recordDate;
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
        TransactionType $type,
        Account $sourceAccount,
        Account $destinationAccount,
        Money $amount,
        TransactionDate $date,
        string $description
    ) : self
    {
        Assert::stringNotEmpty($description, "The transaction description must be provided");

        $id = TransactionId::fromUuid(Uuid::uuid4());

        $recordDate = TransactionRecordDate::now();

        return new self(
            $id,
            $type,
            $sourceAccount,
            $destinationAccount,
            $amount,
            $date,
            $recordDate,
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
     * Get the type of the transaction
     *
     * @return TransactionType
     */
    public function type() : TransactionType
    {
        return $this->type;
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
     * Get the transaction record date
     *
     * @return TransactionRecordDate
     */
    public function recordDate() : TransactionRecordDate
    {
        return $this->recordDate;
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

    /**
     * Check can a transaction be executed
     *
     * @param TransactionType $type
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     * @return bool
     */
    private function canTransactionBeExecuted(TransactionType $type, Account $sourceAccount, Account $destinationAccount) : bool
    {
        if ($type->isPay() && $sourceAccount->canPay($destinationAccount)) {
            return true;
        }

        if ($type->isRefund() && $sourceAccount->canRefund($destinationAccount)) {
            return true;
        }

        return false;
    }
}
