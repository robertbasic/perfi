<?php

namespace PerFi\PerFiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountRepository as AccountRepositoryInterface;
use PerFi\Domain\Account\AccountType;
use PerFi\PerFiBundle\Entity\Account as DtoAccount;

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
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        $queryBuilder = $this->createQueryBuilder('a');

        $dtos = $queryBuilder
            ->select('a')
            ->getQuery()
            ->getResult();

        return $this->dtosToEntities($dtos);
    }

    public function getAllByType(string $type) : array
    {
        $queryBuilder = $this->createQueryBuilder('a');

        $dtos = $queryBuilder
            ->select('a')
            ->where('a.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();

        return $this->dtosToEntities($dtos);
    }

    private function dtosToEntities($dtos)
    {
        $accounts = [];

        foreach ($dtos as $dto) {
            $account = Account::withId(
                AccountId::fromString($dto->getAccountId()),
                AccountType::fromString($dto->getType()),
                $dto->getTitle()
            );

            $accounts[] = $account;
        }

        return $accounts;
    }
}
