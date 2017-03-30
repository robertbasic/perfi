<?php
declare(strict_types=1);

namespace PerFiUnitTest\PerFiBundle\Repository;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\DBAL\Query\QueryBuilder;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionId;
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
     * @var Connection
     */
    private $connection;

    /**
     * @var TransactionId
     */
    private $transactionId;

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

        $this->connection = m::mock(Connection::class);
        $this->connection->shouldReceive('createQueryBuilder')
            ->andReturn($this->queryBuilder);

        $this->transactionId = TransactionId::fromString('fddf4716-6c0e-4f54-b539-d2d480a50d1c');

        $this->transaction = TransactionFactory::fromArray([
            'transaction_id' => (string) $this->transactionId,
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
            'refunded' => '0',
        ]);

        $this->repository = new TransactionRepository($this->connection);
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
                'refunded' => '?',
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
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(9, 0)
            ->andReturnSelf();

        $this->repository->add($this->transaction);
    }

    /**
     * @test
     */
    public function can_save_transaction_to_repository()
    {
        $this->queryBuilder->shouldReceive('update')
            ->once()
            ->with('transaction')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('set')
            ->once()
            ->with('type', '?')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('set')
            ->once()
            ->with('source_account', '?')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('set')
            ->once()
            ->with('destination_account', '?')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('set')
            ->once()
            ->with('amount', '?')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('set')
            ->once()
            ->with('currency', '?')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('set')
            ->once()
            ->with('date', '?')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('set')
            ->once()
            ->with('record_date', '?')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('set')
            ->once()
            ->with('description', '?')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('set')
            ->once()
            ->with('refunded', '?')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('where')
            ->once()
            ->with('transaction_id = ?')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(0, 'pay')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(1, 'fddf4716-6c0e-4f54-b539-d2d480a50d1a')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(2, 'fddf4716-6c0e-4f54-b539-d2d480a50d1b')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(3, 50000)
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(4, 'RSD')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(5, '2017-03-12 00:00:00')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(6, '2017-03-20 06:55:00')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(7, 'supermarket')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(8, 0)
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with(9, 'fddf4716-6c0e-4f54-b539-d2d480a50d1c')
            ->andReturnSelf();

        $this->repository->save($this->transaction);
    }

    /**
     * @test
     * @dataProvider transactions
     */
    public function can_get_transaction_from_repository($transactions)
    {
        $this->queryBuilder->shouldReceive('select')
            ->once()
            ->with(
                't.transaction_id', 't.type', 't.amount', 't.currency',
                't.date', 't.record_date', 't.description', 't.refunded',
                'sa.account_id AS source_account_id',
                'sa.title AS source_account_title',
                'sa.type AS source_account_type',
                'da.account_id AS destination_account_id',
                'da.title AS destination_account_title',
                'da.type AS destination_account_type'
            )
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('from')
            ->once()
            ->with('transaction', 't')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('innerJoin')
            ->once()
            ->with('t', 'account', 'sa', 't.source_account = sa.account_id')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('innerJoin')
            ->once()
            ->with('t', 'account', 'da', 't.destination_account = da.account_id')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('where')
            ->once()
            ->with('t.transaction_id = :transactionId')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('setParameter')
            ->once()
            ->with('transactionId', $this->transactionId)
            ->andReturnSelf();

        $this->statement->shouldReceive('fetch')
            ->once()
            ->andReturn(array_pop($transactions));

        $transaction = $this->repository->get($this->transactionId);

        self::assertInstanceOf(Transaction::class, $transaction);
    }

    /**
     * @test
     * @dataProvider transactions
     */
    public function can_get_all_transactions_from_repository($transactions)
    {
        $this->queryBuilder->shouldReceive('select')
            ->once()
            ->with(
                't.transaction_id', 't.type', 't.amount', 't.currency',
                't.date', 't.record_date', 't.description', 't.refunded',
                'sa.account_id AS source_account_id',
                'sa.title AS source_account_title',
                'sa.type AS source_account_type',
                'da.account_id AS destination_account_id',
                'da.title AS destination_account_title',
                'da.type AS destination_account_type'
            )
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('from')
            ->once()
            ->with('transaction', 't')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('innerJoin')
            ->once()
            ->with('t', 'account', 'sa', 't.source_account = sa.account_id')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('innerJoin')
            ->once()
            ->with('t', 'account', 'da', 't.destination_account = da.account_id')
            ->andReturnSelf();

        $this->statement->shouldReceive('fetch')
            ->andReturnUsing(function() use (&$transactions) { return array_pop($transactions); });

        $result = $this->repository->getAll();

        self::assertInstanceOf(Transaction::class, array_pop($result));
    }

    public function transactions()
    {
        return [
            [
                [[
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
                    'refunded' => '0',
                ]]
            ]
        ];
    }
}
