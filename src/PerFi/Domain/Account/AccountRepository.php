<?php
declare(strict_types=1);

namespace PerFi\Domain\Account;

use PerFi\Domain\Account\Account;

interface AccountRepository
{
    /**
     * Add an account to the repository
     *
     * @param Account $account
     */
    public function add(Account $account);

    /**
     * Get all accounts from the repository
     *
     * @return array
     */
    public function getAll() : array;
}
