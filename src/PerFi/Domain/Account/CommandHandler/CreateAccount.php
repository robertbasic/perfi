<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\CommandHandler;

use PerFi\Domain\Account\AccountRepository;
use PerFi\Domain\Command;
use PerFi\Domain\CommandHandler;

class CreateAccount implements CommandHandler
{
    /**
     * @var AccountRepository
     */
    private $accounts;

    public function __construct(AccountRepository $accounts)
    {
        $this->accounts = $accounts;
    }

    public function __invoke(Command $createAccount)
    {
        $account = $createAccount->payload();

        $this->accounts->add($account);
    }
}
