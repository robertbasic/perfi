<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\Specification;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;

class ExpenseAccount
{
    /**
     * Check is an accout of expense type
     *
     * @param Account $account
     * @return bool
     */
    public function isSatisfiedBy(Account $account) : bool
    {
        return (string) $account->type() === AccountType::ACCOUNT_TYPE_EXPENSE;
    }
}
