<?php
declare(strict_types=1);

namespace PerFi\PerFiBundle\Repository;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

abstract class Repository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function getConnection()
    {
        return $this->connection;
    }

    protected function getQueryBuilder() : QueryBuilder
    {
        return $this->getConnection()->createQueryBuilder();
    }
}
