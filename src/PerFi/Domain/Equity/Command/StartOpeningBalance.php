<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity\Command;

use Money\Currencies\ISOCurrencies;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use PerFi\Domain\Command;

class StartOpeningBalance implements Command
{
    /**
     * @var Money
     */
    private $money;

    public function __construct(string $amount, string $currency)
    {
        $currencies = new ISOCurrencies();
        $moneyParser = new DecimalMoneyParser($currencies);

        $this->money = $moneyParser->parse($amount, $currency);
    }

    public function money() : Money
    {
        return $this->money;
    }
}
