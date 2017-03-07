<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity;

use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\Repository;

interface OpeningBalanceRepository
{
    /**
     * Get all opening balances from the repository
     *
     * @return array
     */
    public function add(OpeningBalance $openingBalance);

    /**
     * Add an opening balance to the repository
     *
     * @param OpeningBalance $openingBalance
     */
    public function getAll() : array;
}
