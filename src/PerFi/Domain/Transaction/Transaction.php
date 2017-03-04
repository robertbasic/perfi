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

    public function id() : TransactionId
    {
        return $this->id;
    }

    public function sourceAccount() : Account
    {
        return $this->sourceAccount;
    }

    public function destinationAccount() : Account
    {
        return $this->destinationAccount;
    }

    public function amount() : Money
    {
        return $this->amount;
    }

    public function date() : TransactionDate
    {
        return $this->date;
    }

    public function description() : string
    {
        return $this->description;
    }
}
