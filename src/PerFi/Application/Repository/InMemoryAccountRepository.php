<?php
declare(strict_types=1);

namespace PerFi\Application\Repository;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountRepository;

class InMemoryAccountRepository implements AccountRepository
{
    /**
     * @var Account[]
     */
    private $accounts;

    /**
     * {@inheritdoc}
     */
    public function save(Account $account)
    {
        $this->accounts[(string) $account->id()] = $account;
    }

    /**
     * {@inheritdoc}
     */
    public function get(AccountId $accountId) : Account
    {
        return $this->accounts[(string) $accountId];
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        return $this->accounts;
    }
}
