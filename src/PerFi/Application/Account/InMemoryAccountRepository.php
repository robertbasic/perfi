<?php
declare(strict_types=1);

namespace PerFi\Application\Account;

use PerFi\Domain\Account\Account;
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
    public function add(Account $account)
    {
        $this->accounts[(string) $account->id()] = $account;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        return $this->accounts;
    }
}
