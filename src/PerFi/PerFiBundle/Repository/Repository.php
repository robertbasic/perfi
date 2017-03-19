<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

abstract class Repository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function getEntityManager() : EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function getQueryBuilder() : QueryBuilder
    {
        return $this->getEntityManager()->getConnection()->createQueryBuilder();
    }
}
