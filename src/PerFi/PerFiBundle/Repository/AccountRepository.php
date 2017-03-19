<?php

namespace PerFi\PerFiBundle\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountRepository as AccountRepositoryInterface;
use PerFi\Domain\Account\AccountType;
use PerFi\PerFiBundle\Factory\AccountFactory;
use PerFi\PerFiBundle\Repository\Repository;

/**
 * AccountRepository
 */
class AccountRepository extends Repository
    implements AccountRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function add(Account $account)
    {
        $qb = $this->getQueryBuilder();

        $query = $qb->insert('account')
            ->values(
                [
                    'account_id' => '?',
                    'title' => '?',
                    'type' => '?',
                ]
            )
            ->setParameter(0, (string) $account->id())
            ->setParameter(1, $account->title())
            ->setParameter(2, (string) $account->type());

        $query->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function get(AccountId $accountId) : Account
    {
        $query = $this->getQuery();

        $statement = $query->where('a.account_id = :accountId')
            ->setParameter('accountId', $accountId)
            ->execute();

        return $this->mapToEntity($statement->fetch());
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        $query = $this->getQuery();

        $statement = $query->from('account', 'a')
            ->execute();

        return $this->mapToEntities($statement);
    }

    /**
     * Get all accounts by an AccountType
     *
     * @param AccountType
     * @return array
     */
    public function getAllByType(AccountType $type) : array
    {
        $query = $this->getQuery();

        $statement = $query->where('a.type = :type')
            ->setParameter('type', $type)
            ->execute();

        return $this->mapToEntities($statement);
    }

    private function getQuery() : QueryBuilder
    {
        $qb = $this->getQueryBuilder();

        $query = $qb->select(
                'a.account_id AS id', 'a.title', 'a.type'
            )
            ->from('account', 'a');

        return $query;
    }

    private function mapToEntities($statement)
    {
        $accounts = [];

        while ($row = $statement->fetch()) {
            $accounts[] = $this->mapToEntity($row);
        }

        return $accounts;
    }

    private function mapToEntity(array $row) : Account
    {
        return AccountFactory::fromArray($row);
    }
}
