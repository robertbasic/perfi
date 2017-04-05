<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\EventSubscriber;

use PerFi\Domain\Account\AccountRepository;
use PerFi\Domain\Account\Event\AccountBalanceChanged;

class SaveAccountWhenAccountBalanceChanged
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     *
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Handle the account balance changed event
     *
     * Update the account in the repository.
     *
     * @param AccountBalanceChanged $event
     */
    public function __invoke(AccountBalanceChanged $event)
    {
        $account = $event->account();

        $this->accountRepository->save($account);
    }
}
