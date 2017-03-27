<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\Specification;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Account\Specification\RefundableAccount;

class RefundableAccounTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var RefundableAccount
     */
    private $specification;

    public function setup()
    {
        $this->specification = new RefundableAccount();
    }

    /**
     * @test
     */
    public function expense_and_asset_accounts_satisfy_specification()
    {
        $expenseAccount = m::mock(Account::class);
        $expenseAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('expense'));

        $assetAccount = m::mock(Account::class);
        $assetAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('asset'));

        $this->specification->isSatisfiedBy($expenseAccount, $assetAccount);
    }

    /**
     * @test
     */
    public function asset_and_expense_accounts_do_not_satisfy_specification()
    {
        $assetAccount = m::mock(Account::class);
        $assetAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('asset'));

        $expenseAccount = m::mock(Account::class);
        $expenseAccount->shouldReceive('type')
            ->never();

        $this->specification->isSatisfiedBy($assetAccount, $expenseAccount);
    }
}
