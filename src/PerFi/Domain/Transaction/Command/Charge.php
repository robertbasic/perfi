<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Command;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\Command\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class Charge extends Transaction
{
    /**
     * Execute charge transaction command
     *
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     * @param Money $amount
     * @param string $description
     */
    public function __construct(
        Account $sourceAccount,
        Account $destinationAccount,
        string $amount,
        string $currency,
        string $description
    )
    {
        parent::__construct(
            $sourceAccount,
            $destinationAccount,
            $amount,
            $currency,
            $description,
            TransactionType::TRANSACTION_TYPE_CHARGE
        );
    }
}
