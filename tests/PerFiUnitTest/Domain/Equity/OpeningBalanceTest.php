<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Equity;

use Money\Money;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\Equity\OpeningBalanceId;
use PerFi\Domain\MoneyFactory;

class OpeningBalanceTest extends TestCase
{
    /**
     * @test
     */
    public function for_starting_an_opening_balance()
    {
        $amount = '500';
        $currency = 'RSD';
        $money = MoneyFactory::amountInCurrency($amount, $currency);

        $openingBalance = OpeningBalance::forStarting($money);

        $id = $openingBalance->id();
        $value = $openingBalance->value();
        $amount = $openingBalance->amount();
        $currency = $openingBalance->currency();

        self::assertInstanceOf(OpeningBalanceId::class, $id);
        self::assertInstanceOf(Money::class, $amount);
        self::assertSame('500', $value);
        self::assertSame('RSD', $currency);
    }
}
