<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\Command;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Command;

class CreateAccount implements Command
{
    /**
     * @var Account
     */
    private $account;

    /**
     * Create account command
     *
     * Creates an account by it's type and with a title.
     *
     * @param string $type
     * @param string $title
     */
    public function __construct(string $type, string $title)
    {
        $this->account = Account::byStringType($type, $title);
    }

    /**
     * The payload of the command
     *
     * @return Account
     */
    public function payload() : Account
    {
        return $this->account;
    }
}
