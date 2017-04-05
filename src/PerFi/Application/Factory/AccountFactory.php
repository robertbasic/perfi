<?php
declare(strict_types=1);

namespace PerFi\Application\Factory;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;

class AccountFactory
{
    /**
     * Create an Account from a row in the database
     *
     * @param array $account
     * @param array $balances
     * @return Account
     */
    public static function fromArray(array $account, array $balances) : Account
    {
        $balanceAmounts = [];

        foreach ($balances as $balance) {
            $balanceAmounts[$balance['currency']][] = MoneyFactory::centsInCurrency($balance['amount'], $balance['currency']);
        }

        return Account::withId(
            AccountId::fromString($account['id']),
            AccountType::fromString($account['type']),
            $account['title'],
            $balanceAmounts
        );
    }
}
