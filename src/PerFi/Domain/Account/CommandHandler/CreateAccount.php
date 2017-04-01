<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\CommandHandler;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountRepository;
use PerFi\Domain\Account\Command\CreateAccount as CreateAccountCommand;

class CreateAccount
{
    /**
     * @var AccountRepository
     */
    private $accounts;

    /**
     * A handler that handles the creation of an account
     *
     * @param AccountRepository $accounts
     */
    public function __construct(AccountRepository $accounts)
    {
        $this->accounts = $accounts;
    }

    /**
     * Handle the create account command
     *
     * Create the account and save it to the repository.
     *
     * @param CreateAccountCommand $command
     */
    public function __invoke(CreateAccountCommand $command)
    {
        $accountType = $command->accountType();
        $title = $command->title();

        $account = Account::byTypeWithTitle($accountType, $title);

        $this->accounts->save($account);
    }
}
