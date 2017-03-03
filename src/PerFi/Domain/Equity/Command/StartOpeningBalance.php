<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity\Command;

use PerFi\Domain\Command;
use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\MoneyFactory;

class StartOpeningBalance implements Command
{
    /**
     * @var OpeningBalance
     */
    private $openingBalance;

    public function __construct(string $amount, string $currency)
    {
        $money = MoneyFactory::amountInCurrency($amount, $currency);

        $this->openingBalance = OpeningBalance::forStarting($money);
    }

    public function payload() : OpeningBalance
    {
        return $this->openingBalance;
    }
}
