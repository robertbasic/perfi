<?php

namespace PerFi\PerFiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountRepository as AccountRepositoryInterface;
use PerFi\Domain\Account\AccountType;
use PerFi\PerFiBundle\Entity\Account as DtoAccount;
use PerFi\PerFiBundle\Factory\AccountFactory;

/**
 * AccountRepository
 */
class AccountRepository extends EntityRepository
    implements AccountRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function add(Account $account)
    {
        $entity = new DtoAccount();
        $entity->setAccountId((string) $account->id());
        $entity->setTitle($account->title());
        $entity->setType((string) $account->type());

        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

    /**
     * Get an account by it's ID
     *
     * @param AccountId $accountId
     * @return Account
     */
    public function get(AccountId $accountId) : Account
    {
        $dto = $this->findOneBy(['accountId' => (string) $accountId]);

        return $this->dtoToEntity($dto);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $statement = $qb->select(
            'a.account_id AS id', 'a.title', 'a.type'
        )
        ->from('account', 'a')
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
        $qb = $this->getEntityManager()->getConnection()->createQueryBuilder();

        $statement = $qb->select(
            'a.account_id AS id', 'a.title', 'a.type'
        )
        ->from('account', 'a')
        ->where('a.type = :type')
        ->setParameter('type', $type)
        ->execute();

        return $this->mapToEntities($statement);
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
