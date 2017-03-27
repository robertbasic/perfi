<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\Specification;

use PerFi\Domain\Account\AccountType;

class ExpenseAccount
{
    public function isSatisfiedBy(AccountType $type)
    {
        return (string) $type === AccountType::ACCOUNT_TYPE_EXPENSE;
    }
}
