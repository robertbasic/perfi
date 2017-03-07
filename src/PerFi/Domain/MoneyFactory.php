<?php
declare(strict_types=1);

namespace PerFi\Domain;

use Money\Currencies\ISOCurrencies;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class MoneyFactory
{
    /**
     * Create a Money value object with the provided amount and currency
     *
     * @param string $amount
     * @param string $currency
     * @return Money
     */
    public static function amountInCurrency(string $amount, string $currency) : Money
    {
        $currencies = new ISOCurrencies();
        $moneyParser = new DecimalMoneyParser($currencies);

        return $moneyParser->parse($amount, $currency);
    }
}
