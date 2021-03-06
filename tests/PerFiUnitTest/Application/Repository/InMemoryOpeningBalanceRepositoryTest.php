<?php
declare(strict_types=1);

namespace PerFiUnitTest\Application\Repository;

use PHPUnit\Framework\TestCase;
use PerFi\Application\Repository\InMemoryOpeningBalanceRepository;
use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\MoneyFactory;

class InMemoryOpeningBalanceRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function save_opening_balance_added_per_currency()
    {
        $repository = new InMemoryOpeningBalanceRepository();

        $amount = '500';
        $currency = 'RSD';
        $money = MoneyFactory::amountInCurrency($amount, $currency);
        $openingBalance = OpeningBalance::forStarting($money);

        $repository->save($openingBalance);

        $balances = $repository->getAll();

        foreach ($balances as $balanceCurrency => $balancesInCurrency) {
            self::assertSame($currency, $balanceCurrency);

            foreach ($balancesInCurrency as $balance) {
                $balanceAmount = $balance->value();
                self::assertEquals($amount, $balanceAmount);
            }
        }
    }

    /**
     * @test
     */
    public function totals_are_calculated_per_currency()
    {
        $repository = new InMemoryOpeningBalanceRepository();

        $amount = '500';
        $currency = 'RSD';
        $money = MoneyFactory::amountInCurrency($amount, $currency);
        $openingBalance = OpeningBalance::forStarting($money);

        $repository->save($openingBalance);

        $amount = '600';
        $currency = 'RSD';
        $money = MoneyFactory::amountInCurrency($amount, $currency);
        $openingBalance = OpeningBalance::forStarting($money);

        $repository->save($openingBalance);

        $amount = '60';
        $currency = 'EUR';
        $money = MoneyFactory::amountInCurrency($amount, $currency);
        $openingBalance = OpeningBalance::forStarting($money);

        $repository->save($openingBalance);

        $totals = $repository->getTotals();

        $expected = [
            'RSD' => '1100',
            'EUR' => '60',
        ];

        foreach ($totals as $currency => $total) {
            $expectedTotal = MoneyFactory::amountInCurrency($expected[$currency], $currency);

            self::assertArrayHasKey($currency, $expected);
            self::assertTrue($expectedTotal->equals($total));
        }
    }
}
