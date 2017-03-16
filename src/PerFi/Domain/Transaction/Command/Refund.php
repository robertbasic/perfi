<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Command;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\Command\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class Refund extends Transaction
{
    /**
     * Execute refund transaction command
     *
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     * @param Money $amount
     * @param string $date
     * @param string $description
     */
    public function __construct(
        Account $sourceAccount,
        Account $destinationAccount,
        string $amount,
        string $currency,
        string $date,
        string $description
    )
    {
        parent::__construct(
            $sourceAccount,
            $destinationAccount,
            $amount,
            $currency,
            $date,
            $description,
            TransactionType::TRANSACTION_TYPE_REFUND
        );
    }
}
