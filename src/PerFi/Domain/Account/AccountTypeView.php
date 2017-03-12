<?php
declare(strict_types=1);

namespace PerFi\Domain\Account;

use PerFi\Domain\Account\AccountType;

class AccountTypeView
{
    /**
     * Return an array of account types
     *
     * The keys are the labels, the values are the values.
     * Good for a Symfony form ChoiceType.
     *
     * @return array
     */
    public static function getTypes() : array
    {
        $types = AccountType::ACCOUNT_TYPES;
        return array_combine(array_map('ucfirst', $types), $types);
    }
}
