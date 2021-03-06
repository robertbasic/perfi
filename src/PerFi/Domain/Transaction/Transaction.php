<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

use Money\Money;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\Specification\NotRefundedTransaction;
use PerFi\Domain\Transaction\Specification\PayTransaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionId;
use PerFi\Domain\Transaction\TransactionRecordDate;
use PerFi\Domain\Transaction\TransactionType;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Transaction implements \JsonSerializable
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
     * @var bool
     */
    private $refunded;

    /**
     * Create a transaction
     *
     * @param TransactionId $id
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     * @param Money $amount
     * @param TransactionDate $date
     * @param string $description
     * @param bool $refunded
     */
    private function __construct(
        TransactionId $id,
        TransactionType $type,
        Account $sourceAccount,
        Account $destinationAccount,
        Money $amount,
        TransactionDate $date,
        TransactionRecordDate $recordDate,
        string $description,
        bool $refunded
    )
    {
        Assert::stringNotEmpty($description, "The transaction description must be provided");

        $this->id = $id;
        $this->type = $type;
        $this->sourceAccount = $sourceAccount;
        $this->destinationAccount = $destinationAccount;
        $this->amount = $amount;
        $this->date = $date;
        $this->recordDate = $recordDate;
        $this->description = $description;
        $this->refunded = $refunded;
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
            $description,
            false
        );
    }

    /**
     * Create an existing transaction
     *
     * @param TransactionId $id
     * @param TransactionType $type
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     * @param Money $amount
     * @param TransactionDate $date
     * @param TransactionRecordDate $recordDate
     * @param string $description
     * @param bool $refunded
     * @return Transaction
     */
    public static function withId(
        TransactionId $id,
        TransactionType $type,
        Account $sourceAccount,
        Account $destinationAccount,
        Money $amount,
        TransactionDate $date,
        TransactionRecordDate $recordDate,
        string $description,
        bool $refunded
    ) : self
    {
        return new self(
            $id,
            $type,
            $sourceAccount,
            $destinationAccount,
            $amount,
            $date,
            $recordDate,
            $description,
            $refunded
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
     * Mark the transaction as refunded
     */
    public function markAsRefunded()
    {
        $this->refunded = true;
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
     * Get the refunded flag
     *
     * @return bool
     */
    public function refunded() : bool
    {
        return $this->refunded;
    }

    /**
     * Check can a transaction be refunded
     *
     * Only a not-refunded Pay transaction can be refunded.
     *
     * @return bool
     */
    private function canBeRefunded() : bool
    {
        $payTransactionSpecification = new PayTransaction();
        if (!$payTransactionSpecification->isSatisfiedBy($this)) {
            return false;
        }

        $notRefundedTransactionSpecification = new NotRefundedTransaction();
        if (!$notRefundedTransactionSpecification->isSatisfiedBy($this)) {
            return false;
        }

        return true;
    }

    /**
     * JSON serializeable object
     *
     * @return array
     */
    public function jsonSerialize() : array
    {
        $amount = $this->amount();

        return [
            'id' => (string) $this->id(),
            'type' => (string) $this->type(),
            'source_account' => (string) $this->sourceAccount(),
            'destination_account' => (string) $this->destinationAccount(),
            'amount' => number_format($amount->getAmount() / 100, 2) . ' ' . (string) $amount->getCurrency(),
            'date' => (string) $this->date(),
            'description' => $this->description(),
            'refundable' => $this->canBeRefunded(),
        ];
    }
}
