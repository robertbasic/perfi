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

    public function __construct(string $type, string $title)
    {
        $this->account = Account::byStringType($type, $title);
    }

    public function payload() : Account
    {
        return $this->account;
    }
}
