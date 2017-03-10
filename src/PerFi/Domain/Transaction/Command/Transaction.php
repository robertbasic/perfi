<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Command;

use Money\Money;
use PerFi\Domain\Account\Account;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\TransactionType;

abstract class Transaction
{
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
     * Execute transaction command
     *
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     * @param Money $amount
     * @param string $description
     * @param string $transactionType
     */
    public function __construct(
        Account $sourceAccount,
        Account $destinationAccount,
        string $amount,
        string $currency,
        string $description,
        string $transactionType
    )
    {
        $this->transactionType = TransactionType::fromString(
            $transactionType
        );
        $this->sourceAccount = $sourceAccount;
        $this->destinationAccount = $destinationAccount;
        $this->amount = MoneyFactory::amountInCurrency($amount, $currency);
        $this->description = $description;
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
}
