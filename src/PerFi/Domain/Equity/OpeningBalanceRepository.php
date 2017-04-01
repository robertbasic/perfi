<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity;

use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\Repository;

interface OpeningBalanceRepository
{
    /**
     * Save an opening balance to the repository
     *
     * @return array
     */
    public function save(OpeningBalance $openingBalance);

    /**
     * Get all opening balances from the repository
     *
     * @param OpeningBalance $openingBalance
     */
    public function getAll() : array;
}
