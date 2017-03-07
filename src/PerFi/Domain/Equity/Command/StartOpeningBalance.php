<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity\Command;

use Money\Money;
use PerFi\Domain\Command;
use PerFi\Domain\Equity\OpeningBalance;

class StartOpeningBalance implements Command
{
    /**
     * @var OpeningBalance
     */
    private $openingBalance;

    /**
     * Start opening balance command
     *
     * Creates an opening balance with the given Money amount.
     *
     * @param Money $amount
     */
    public function __construct(Money $amount)
    {
        $this->openingBalance = OpeningBalance::forStarting($amount);
    }

    /**
     * The payload of the command
     *
     * @return OpeningBalance
     */
    public function payload() : OpeningBalance
    {
        return $this->openingBalance;
    }
}
