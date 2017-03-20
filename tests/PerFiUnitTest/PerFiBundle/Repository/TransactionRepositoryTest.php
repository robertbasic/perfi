<?php
declare(strict_types=1);

namespace PerFiUnitTest\PerFiBundle\Repository;

use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Transaction\Transaction;
use PerFi\PerFiBundle\Factory\TransactionFactory;
use PerFi\PerFiBundle\Repository\TransactionRepository;

class TransactionRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var TransactionRepository
     */
    private $repository;

    /**
     * @var PDOStatement
     */
    private $statement;

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Transaction
     */
    private $transaction;

    public function setup()
    {
        $this->statement = m::mock(PDOStatement::class);

        $this->queryBuilder = m::mock(QueryBuilder::class);
        $this->queryBuilder->shouldReceive('execute')
            ->andReturn($this->statement)
            ->byDefault();

        $this->entityManager = m::mock(EntityManagerInterface::class);
        $this->entityManager->shouldReceive('getConnection->createQueryBuilder')
            ->andReturn($this->queryBuilder);

        $this->transaction = TransactionFactory::fromArray([
            'transaction_id' => 'fddf4716-6c0e-4f54-b539-d2d480a50d1c',
            'type' => 'pay',
            'source_account_id' => 'fddf4716-6c0e-4f54-b539-d2d480a50d1a',
            'source_account_type' => 'asset',
            'source_account_title' => 'Cash',
            'destination_account_id' => 'fddf4716-6c0e-4f54-b539-d2d480a50d1b',
            'destination_account_type' => 'expense',
            'destination_account_title' => 'Groceries',
            'amount' => '50000',
            'currency' => 'RSD',
            'date' => '2017-03-12',
            'record_date' => '2017-03-20 06:55:00',
            'description' => 'supermarket',
        ]);

        $this->repository = new TransactionRepository($this->entityManager);
    }

    /**
     * @test
     */
    public function can_add_transaction_to_repository()
    {
        $this->queryBuilder->shouldReceive('insert')
            ->once()
            ->with('transaction')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('values')
            ->once()
            ->with([
                'transaction_id' => '?',
                'type' => '?',
                'source_account' => '?',
                'destination_account' => '?',
                'amount' => '?',
                'currency' => '?',
                'date' => '?',
                'record_date' => '?',
                'description' => '?',
            ])
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(0, 'fddf4716-6c0e-4f54-b539-d2d480a50d1c')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(1, 'pay')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(2, 'fddf4716-6c0e-4f54-b539-d2d480a50d1a')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(3, 'fddf4716-6c0e-4f54-b539-d2d480a50d1b')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(4, 50000)
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(5, 'RSD')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(6, '2017-03-12 00:00:00')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(7, '2017-03-20 06:55:00')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(8, 'supermarket')
            ->andReturnSelf();

        $this->repository->add($this->transaction);
    }
}
