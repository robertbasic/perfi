<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\Specification;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Account\Specification\ExpenseAccount;

class ExpenseAccountTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var ExpenseAccount
     */
    private $specification;

    public function setup()
    {
        $this->specification = new ExpenseAccount();
    }

    /**
     * @test
     */
    public function expense_account_type_satisfies_specification()
    {
        $account = m::mock(Account::class);
        $account->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('expense'));

        $result = $this->specification->isSatisfiedBy($account);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function asset_account_type_does_not_satisfy_specification()
    {
        $account = m::mock(Account::class);
        $account->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('asset'));

        $result = $this->specification->isSatisfiedBy($account);

        self::assertFalse($result);
    }
}
