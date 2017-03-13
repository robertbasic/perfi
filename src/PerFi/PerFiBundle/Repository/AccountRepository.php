<?php

namespace PerFi\PerFiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountRepository as AccountRepositoryInterface;
use PerFi\Domain\Account\AccountType;
use PerFi\PerFiBundle\Entity;

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
        $entity = new Entity\Account();
        $entity->setAccountId((string) $account->id());
        $entity->setTitle($account->title());
        $entity->setType((string) $account->type());

        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        $queryBuilder = $this->createQueryBuilder('a');

        return $queryBuilder
            ->select('a')
            ->getQuery()
            ->getArrayResult();
    }

    public function getAllByType(string $type) : array
    {
        $queryBuilder = $this->createQueryBuilder('a');

        $rows = $queryBuilder
            ->select('a')
            ->where('a.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();

        $accounts = [];

        foreach ($rows as $row) {
            $account = Account::withId(
                AccountId::fromString($row->getAccountId()),
                AccountType::fromString($row->getType()),
                $row->getTitle()
            );

            $accounts[] = $account;
        }

        return $accounts;
    }
}
