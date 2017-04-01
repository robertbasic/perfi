<?php
declare(strict_types=1);

namespace PerFi\Domain\Account;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;

interface AccountRepository
{
    /**
     * Save an account to the repository
     *
     * @param Account $account
     */
    public function save(Account $account);

    /**
     * Get an account from the repository
     *
     * @param AccountId $accountId
     * @return Account
     */
    public function get(AccountId $accountId) : Account;

    /**
     * Get all accounts from the repository
     *
     * @return array
     */
    public function getAll() : array;
}
