<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\Event;

use PerFi\Domain\Account\Account;

class AccountBalanceChanged
{
    /**
     * @var Account
     */
    private $account;

    /**
     * Create an account balance changed event
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * The account for which the balance was changed
     *
     * @return Account
     */
    public function account() : Account
    {
        return $this->account;
    }
}
