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
        // @todo needs to track per currency
        $this->openingBalances[(string) $openingBalance->id()] = $openingBalance;
    }

    public function getAll() : array
    {
        return $this->openingBalances;
    }

    public function getTotal() : Money
    {
        // @todo needs to add totals per currency
        $total = Money::RSD(0);

        foreach ($this->getAll() as $openingBalance) {
            $total = $total->add($openingBalance->amount());
        }

        return $total;
    }
}
