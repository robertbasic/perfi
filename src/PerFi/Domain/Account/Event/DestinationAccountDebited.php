<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\Event;

use PerFi\Domain\Account\Account;

class DestinationAccountDebited
{
    /**
     * Create a destination account debited event
     *
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * The account that was debited
     *
     * @return Account
     */
    public function account() : Account
    {
        return $this->account;
    }
}
