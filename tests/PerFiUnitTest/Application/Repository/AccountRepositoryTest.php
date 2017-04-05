<?php
declare(strict_types=1);

namespace PerFiUnitTest\Application\Repository;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\PDOStatement;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\AccountTypeTrait;
use PerFiUnitTest\Traits\AmountTrait;
use PerFiUnitTest\Traits\QueryBuilderTrait;
use PerFi\Application\Factory\AccountFactory;
use PerFi\Application\Repository\AccountRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountType;

class AccountRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use AccountTypeTrait;
    use AmountTrait;
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
        $this->accountType = $this->asset();
        $this->accountTitle = 'Cash';

        $account = [
            'id' => (string) $this->accountId,
            'type' => (string) $this->accountType,
            'title' => $this->accountTitle,
        ];
        $balances = [];

        $this->account = AccountFactory::fromArray($account, $balances);

        $this->repository = new AccountRepository($this->connection);
    }

    /**
     * @test
     */
    public function can_insert_new_account_to_repository()
    {
        $this->mockAccountExistsQuery();

        $this->statement->shouldReceive('fetch')
            ->once()
            ->andReturn(false);

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
     */
    public function can_insert_new_balances_for_existing_account()
    {
        $amount = $this->amount('500', 'RSD');
        $this->account->credit($amount);

        $this->mockAccountExistsQuery();

        $this->statement->shouldReceive('fetch')
            ->once()
            ->andReturn(true);

        $this->mockBalanceExistsQuery();

        $this->statement->shouldReceive('fetch')
            ->once()
            ->andReturn(false);

        $this->queryBuilder->shouldReceive('insert')
            ->once()
            ->with('balance')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('values')
            ->once()
            ->with([
                'account_id' => '?',
                'amount' => '?',
                'currency' => '?',
            ])
            ->andReturnSelf();

        $this->mockSetPositionalParameter(0, (string) $this->accountId);
        $this->mockSetPositionalParameter(1, -50000);
        $this->mockSetPositionalParameter(2, 'RSD');

        $this->repository->save($this->account);
    }

    /**
     * @test
     */
    public function can_update_existing_balances_for_existing_account()
    {
        $amount = $this->amount('500', 'RSD');
        $this->account->credit($amount);

        $this->mockAccountExistsQuery();

        $this->statement->shouldReceive('fetch')
            ->once()
            ->andReturn(true);

        $this->mockBalanceExistsQuery();

        $this->statement->shouldReceive('fetch')
            ->once()
            ->andReturn(true);

        $this->queryBuilder->shouldReceive('update')
            ->once()
            ->with('balance')
            ->andReturnSelf();

        $this->mockSetValue('amount');

        $expressionBuilder = m::mock(ExpressionBuilder::class);
        $expression = m::mock(CompositeExpression::class);
        $expressionBuilder->shouldReceive('eq')
            ->once()
            ->with('account_id', '?')
            ->andReturn('account_id = ?');
        $expressionBuilder->shouldReceive('eq')
            ->once()
            ->with('currency', '?')
            ->andReturn('currency = ?');
        $expressionBuilder->shouldReceive('andX')
            ->with('account_id = ?', 'currency = ?')
            ->andReturn($expression);

        $this->queryBuilder->shouldReceive('expr')
            ->once()
            ->andReturn($expressionBuilder);

        $this->queryBuilder->shouldReceive('where')
            ->once()
            ->with($expression)
            ->andReturnSelf();

        $this->mockSetPositionalParameter(0, -50000);
        $this->mockSetPositionalParameter(1, (string) $this->accountId);
        $this->mockSetPositionalParameter(2, 'RSD');

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

        $this->mockGetBalances();

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

        $this->mockGetBalances();

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

        $this->mockGetBalances();

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

    private function mockAccountExistsQuery()
    {
        $this->queryBuilder->shouldReceive('select')
            ->once()
            ->with('a.account_id')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('from')
            ->once()
            ->with('account', 'a')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('where')
            ->once()
            ->with('a.account_id = :accountId')
            ->andReturnSelf();

        $this->mockSetNamedParameter('accountId', 'fddf4716-6c0e-4f54-b539-d2d480a50d1a');
    }

    private function mockBalanceExistsQuery()
    {
        $this->queryBuilder->shouldReceive('select')
            ->once()
            ->with('b.id')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('from')
            ->once()
            ->with('balance', 'b')
            ->andReturnSelf();

        $expressionBuilder = m::mock(ExpressionBuilder::class);
        $expression = m::mock(CompositeExpression::class);
        $expressionBuilder->shouldReceive('eq')
            ->once()
            ->with('account_id', '?')
            ->andReturn('account_id = ?');
        $expressionBuilder->shouldReceive('eq')
            ->once()
            ->with('currency', '?')
            ->andReturn('currency = ?');
        $expressionBuilder->shouldReceive('andX')
            ->with('account_id = ?', 'currency = ?')
            ->andReturn($expression);

        $this->queryBuilder->shouldReceive('expr')
            ->once()
            ->andReturn($expressionBuilder);

        $this->queryBuilder->shouldReceive('where')
            ->once()
            ->with($expression)
            ->andReturnSelf();

        $this->mockSetPositionalParameter(0, 'fddf4716-6c0e-4f54-b539-d2d480a50d1a');
        $this->mockSetPositionalParameter(1, 'RSD');
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

    private function mockGetBalances()
    {
        $balances = [
            [
                'amount' => '50000',
                'currency' => 'RSD',
            ],
            [
                'amount' => '500',
                'currency' => 'EUR',
            ],
        ];

        $this->queryBuilder->shouldReceive('select')
            ->once()
            ->with('b.amount', 'b.currency')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('from')
            ->once()
            ->with('balance', 'b')
            ->andReturnSelf();
        $this->queryBuilder->shouldReceive('where')
            ->once()
            ->with('account_id = ?')
            ->andReturnSelf();
        $this->mockSetPositionalParameter(0, (string) $this->accountId);

        $this->statement->shouldReceive('fetchAll')
            ->once()
            ->andReturn($balances);
    }
}
