<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity;

use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\Repository;

interface OpeningBalanceRepository
{
    public function add(OpeningBalance $openingBalance);

    public function getAll() : array;
}
