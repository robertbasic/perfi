<?php
declare(strict_types=1);

namespace PerFiUnitTest\Application\Repository;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\DBAL\Query\QueryBuilder;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\QueryBuilderTrait;
use PerFi\Application\Factory\AccountFactory;
use PerFi\Application\Repository\AccountRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountType;

class AccountRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use QueryBuilderTrait;

    /**
     * @var AccountRepository
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
     * @var AccountId
     */
    private $accountId;

    /**
     * @var string
     */
    private $accountTitle;

    /**
     * @var AccountType
     */
    private $accountType;

    /**
     * @var Account
     */
    private $account;

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

        $this->accountId = AccountId::fromString('fddf4716-6c0e-4f54-b539-d2d480a50d1a');
        $this->accountType = AccountType::fromString('asset');
        $this->accountTitle = 'Cash';

        $this->account = AccountFactory::fromArray([
            'id' => (string) $this->accountId,
            'type' => (string) $this->accountType,
            'title' => $this->accountTitle,
        ]);

        $this->repository = new AccountRepository($this->connection);
    }

    /**
     * @test
     */
    public function can_save_account_to_repository()
    {
        $this->queryBuilder->shouldReceive('insert')
            ->once()
            ->with('account')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('values')
            ->once()
            ->with([
                'account_id' => '?',
                'title' => '?',
                'type' => '?',
            ])
            ->andReturnSelf();

        $this->mockSetPositionalParameter(0, (string) $this->accountId);
        $this->mockSetPositionalParameter(1, $this->accountTitle);
        $this->mockSetPositionalParameter(2, (string) $this->accountType);

        $this->repository->save($this->account);
    }

    /**
     * @test
     * @dataProvider accounts
     */
    public function can_get_account_from_repository($accounts)
    {
        $this->mockSelectFrom();

        $this->queryBuilder->shouldReceive('where')
            ->once()
            ->with('a.account_id = :accountId')
            ->andReturnSelf();

        $this->mockSetNamedParameter('accountId', $this->accountId);

        $this->statement->shouldReceive('fetch')
            ->once()
            ->andReturn(array_pop($accounts));

        $account = $this->repository->get($this->accountId);

        self::assertInstanceOf(Account::class, $account);
    }

    /**
     * @test
     * @dataProvider accounts
     */
    public function can_get_all_accounts_from_repository($accounts)
    {
        $this->mockSelectFrom();

        $this->statement->shouldReceive('fetch')
            ->andReturnUsing(function() use (&$accounts) { return array_pop($accounts); });

        $result = $this->repository->getAll();

        self::assertInstanceOf(Account::class, array_pop($result));
    }

    /**
     * @test
     * @dataProvider accounts
     */
    public function can_get_all_accounts_from_repository_by_type($accounts)
    {
        $this->mockSelectFrom();

        $this->queryBuilder->shouldReceive('where')
            ->once()
            ->with('a.type = :type')
            ->andReturnSelf();

        $this->mockSetNamedParameter('type', $this->accountType);

        $this->statement->shouldReceive('fetch')
            ->andReturnUsing(function() use (&$accounts) { return array_pop($accounts); });

        $result = $this->repository->getAllByType($this->accountType);

        self::assertInstanceOf(Account::class, array_pop($result));
    }

    public function accounts()
    {
        return [
            [
                [[
                    'id' => 'fddf4716-6c0e-4f54-b539-d2d480a50d1a',
                    'title' => 'Cash',
                    'type' => 'asset',
                ]]
            ]
        ];
    }

    private function mockSelectFrom()
    {
        $this->queryBuilder->shouldReceive('select')
            ->once()
            ->with('a.account_id AS id', 'a.title', 'a.type')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('from')
            ->once()
            ->with('account', 'a')
            ->andReturnSelf();
    }
}
