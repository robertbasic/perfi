<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity\Command;

use Money\Money;
use PerFi\Domain\MoneyFactory;

class StartOpeningBalance
{
    /**
     * @var Money
     */
    private $amount;

    /**
     * Start opening balance command
     *
     * Creates the amount for the opening balance.
     *
     * @param Money $amount
     */
    public function __construct(string $amount, string $currency)
    {
        $this->amount = MoneyFactory::amountInCurrency($amount, $currency);
    }

    /**
     * The amount for the opening balance
     *
     * @return Money
     */
    public function amount() : Money
    {
        return $this->amount;
    }
}
