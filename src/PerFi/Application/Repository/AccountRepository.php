<?php
declare(strict_types=1);

namespace PerFi\Application\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Money\Money;
use PerFi\Application\Factory\AccountFactory;
use PerFi\Application\Repository\Repository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountRepository as AccountRepositoryInterface;
use PerFi\Domain\Account\AccountType;

/**
 * AccountRepository
 */
class AccountRepository extends Repository
    implements AccountRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(Account $account)
    {
        if ($this->exists($account)) {
            return $this->update($account);
        }

        return $this->insert($account);
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

        $statement = $query->execute();

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

    private function exists(Account $account) : bool
    {
        $qb = $this->getQueryBuilder();

        $statement = $qb->select('a.account_id')
            ->from('account', 'a')
            ->where('a.account_id = :accountId')
            ->setParameter('accountId', (string) $account->id())
            ->execute();

        return (bool) $statement->fetch();
    }

    private function insert(Account $account)
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

    private function update(Account $account)
    {
        $accountId = $account->id();

        $balances = $account->balances();

        $this->saveBalances($accountId, $balances);
    }

    private function getBalances(AccountId $accountId)
    {
        $qb = $this->getQueryBuilder();

        $statement = $qb->select('b.amount', 'b.currency')
            ->from('balance', 'b')
            ->where('account_id = ?')
            ->setParameter(0, (string) $accountId)
            ->execute();

        return $statement->fetchAll();
    }

    private function saveBalances(AccountId $accountId, array $balances)
    {
        foreach ($balances as $amount) {
            if (!$this->balanceExists($accountId, $amount)) {
                $this->insertBalance($accountId, $amount);
            } else {
                $this->updateBalance($accountId, $amount);
            }
        }
    }

    private function balanceExists(AccountId $accountId, Money $amount) : bool
    {
        $qb = $this->getQueryBuilder();

        $eb = $qb->expr();

        $statement = $qb->select('b.id')
            ->from('balance', 'b')
            ->where(
                $eb->andX(
                    $eb->eq('account_id', '?'),
                    $eb->eq('currency', '?')
                )
            )
            ->setParameter(0, (string) $accountId)
            ->setParameter(1, (string) $amount->getCurrency())
            ->execute();

        return (bool) $statement->fetch();
    }

    private function insertBalance(AccountId $accountId, Money $amount)
    {
        $qb = $this->getQueryBuilder();

        $query = $qb->insert('balance')
            ->values(
                [
                    'account_id' => '?',
                    'amount' => '?',
                    'currency' => '?',
                ]
            )
            ->setParameter(0, (string) $accountId)
            ->setParameter(1, (int) $amount->getAmount())
            ->setParameter(2, (string) $amount->getCurrency());

        $query->execute();
    }

    private function updateBalance(AccountId $accountId, Money $amount)
    {
        $qb = $this->getQueryBuilder();

        $eb = $qb->expr();

        $query = $qb->update('balance')
            ->set('amount', '?')
            ->where(
                $eb->andX(
                    $eb->eq('account_id', '?'),
                    $eb->eq('currency', '?')
                )
            )
            ->setParameter(0, (int) $amount->getAmount())
            ->setParameter(1, (string) $accountId)
            ->setParameter(2, (string) $amount->getCurrency());

        $query->execute();
    }

    private function mapToEntities($statement) : array
    {
        $accounts = [];

        while ($row = $statement->fetch()) {
            $accounts[] = $this->mapToEntity($row);
        }

        return $accounts;
    }

    private function mapToEntity(array $row) : Account
    {
        $balances = $this->getBalances(AccountId::fromString($row['id']));

        return AccountFactory::fromArray($row, $balances);
    }
}
