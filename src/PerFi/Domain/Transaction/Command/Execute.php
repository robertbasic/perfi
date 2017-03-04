<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Command;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Command;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Transaction;

class Execute implements Command
{
    /**
     * @var Transaction
     */
    private $transaction;

    public function __construct(
        Account $sourceAccount,
        Account $destinationAccount,
        string $amount,
        string $currency,
        string $description
    )
    {
        $amount = MoneyFactory::amountInCurrency($amount, $currency);

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
