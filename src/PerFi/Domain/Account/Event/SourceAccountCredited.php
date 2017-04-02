<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\Event;

use PerFi\Domain\Account\Account;

class SourceAccountCredited
{
    /**
     * Create a source account credited event
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * The account that was credited
     *
     * @return Account
     */
    public function account() : Account
    {
        return $this->account;
    }
}
