<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Equity\Command;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Equity\Command\StartOpeningBalance;
use PerFi\Domain\MoneyFactory;

class StartOpeningBalanceTest extends TestCase
{
    /**
     * @test
     */
    public function amount_for_opening_balance_is_created()
    {
        $amount = '500';
        $currency = 'RSD';

        $command = new StartOpeningBalance($amount, $currency);

        $result = $command->amount();

        $expected = MoneyFactory::amountInCurrency('500', 'RSD');

        self::assertTrue($expected->equals($result));
    }
}
