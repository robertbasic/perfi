<?php
declare(strict_types=1);

namespace PerFiUnitTest\Application\Equity;

use PHPUnit\Framework\TestCase;
use PerFi\Application\Equity\InMemoryOpeningBalanceRepository;
use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\MoneyFactory;

class InMemoryOpeningBalanceRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function adding_opening_balance_added_per_currency()
    {
        $amount = '500';
        $currency = 'RSD';
        $money = MoneyFactory::amountInCurrency($amount, $currency);

        $openingBalance = OpeningBalance::forStarting($money);

        $repository = new InMemoryOpeningBalanceRepository();
        $repository->add($openingBalance);

        $balances = $repository->getALl();

        foreach ($balances as $balanceCurrency => $balancesInCurrency) {
            self::assertSame($currency, $balanceCurrency);

            foreach ($balancesInCurrency as $balance) {
                $balanceAmount = $balance->value();
                self::assertEquals($amount, $balanceAmount);
            }
        }
    }
}
