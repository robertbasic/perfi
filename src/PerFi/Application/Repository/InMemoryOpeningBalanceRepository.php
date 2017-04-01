<?php
declare(strict_types=1);

namespace PerFi\Application\Repository;

use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\Equity\OpeningBalanceRepository;
use PerFi\Domain\MoneyFactory;

class InMemoryOpeningBalanceRepository implements OpeningBalanceRepository
{
    /**
     * @var array
     */
    private $openingBalances;

    /**
     * {@inheritdoc}
     */
    public function save(OpeningBalance $openingBalance)
    {
        $currency = $openingBalance->currency();

        $this->openingBalances[$currency][(string) $openingBalance->id()] = $openingBalance;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        return $this->openingBalances;
    }

    /**
     * Get the totals for all the balances in the repository
     *
     * @return array
     */
    public function getTotals() : array
    {
        $totals = [];

        foreach ($this->getAll() as $currency => $balances) {
            $total = MoneyFactory::amountInCurrency('0', $currency);

            foreach ($balances as $openingBalance) {
                if ($currency === $openingBalance->currency()) {
                    $total = $total->add($openingBalance->amount());
                }
            }

            $totals[$currency] = $total;
        }


        return $totals;
    }
}
