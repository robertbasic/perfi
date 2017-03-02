<?php
declare(strict_types=1);

namespace PerFi\Application\Equity;

use Money\Money;
use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\Equity\OpeningBalanceRepository;

class InMemoryOpeningBalanceRepository implements OpeningBalanceRepository
{
    /**
     * @var array
     */
    private $openingBalances;

    public function add(OpeningBalance $openingBalance)
    {
        $currency = $openingBalance->currency();

        $this->openingBalances[$currency][(string) $openingBalance->id()] = $openingBalance;
    }

    public function getAll() : array
    {
        return $this->openingBalances;
    }

    public function getTotals() : array
    {
        $totals = [];

        foreach ($this->getAll() as $currency => $balances) {
            $total = Money::{$currency}(0);

            foreach ($balances as $openingBalance) {
                if ($currency === $openingBalance->currency()) {
                    $total = $total->add($openingBalance->amount());
                }
            }

            $totals[] = $total;
        }


        return $totals;
    }
}
