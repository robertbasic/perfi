<?php
declare(strict_types=1);

namespace PerFi\Domain\Account;

use PerFi\Domain\Account\Account;

interface AccountRepository
{
    public function add(Account $account);

    public function getAll() : array;
}
