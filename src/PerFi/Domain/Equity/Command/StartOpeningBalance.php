<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity\Command;

use Money\Currencies\ISOCurrencies;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use PerFi\Domain\Command;
use PerFi\Domain\MoneyFactory;

class StartOpeningBalance implements Command
{
    /**
     * @var Money
     */
    private $money;

    public function __construct(string $amount, string $currency)
    {
        $this->money = MoneyFactory::amountInCurrency($amount, $currency);
    }

    public function money() : Money
    {
        return $this->money;
    }
}
