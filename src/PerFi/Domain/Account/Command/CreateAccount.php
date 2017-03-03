<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\Command;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Command;

class CreateAccount implements Command
{
    /**
     * @var Accout
     */
    private $account;

    public function __construct(string $type, string $title)
    {
        $accountType = AccountType::fromString($type);

        $this->account = Account::byType($accountType, $title);
    }

    public function payload() : Account
    {
        return $this->account;
    }
}
