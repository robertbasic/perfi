<?php
declare(strict_types=1);

namespace PerFiUnitTest\Traits;

use Money\Money;
use PerFi\Domain\MoneyFactory;

trait AmountTrait
{
    public function amount(string $amount, string $currency) : Money
    {
        return MoneyFactory::amountInCurrency($amount, $currency);
    }
}
