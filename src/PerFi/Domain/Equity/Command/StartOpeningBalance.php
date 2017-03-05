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

    public function __construct(Money $amount)
    {
        $this->openingBalance = OpeningBalance::forStarting($amount);
    }

    public function payload() : OpeningBalance
    {
        return $this->openingBalance;
    }
}
