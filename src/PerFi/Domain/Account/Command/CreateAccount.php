<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\Command;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;

class CreateAccount
{
    /**
     * @var AccountType
     */
    private $accountType;

    /**
     * @var string
     */
    private $title;

    /**
     * Create account command
     *
     * @param string $type
     * @param string $title
     */
    public function __construct(string $type, string $title)
    {
        $this->accountType = AccountType::fromString($type);
        $this->title = $title;
    }

    /**
     * Get the type of the account to be
     *
     * @return AccountType
     */
    public function accountType() : AccountType
    {
        return $this->accountType;
    }

    /**
     * Get the title of the account to be
     *
     * @return string
     */
    public function title() : string
    {
        return $this->title;
    }
}
