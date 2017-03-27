<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\Specification;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Account\Specification\PayableAccount;

class PayableAccounTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var PayableAccount
     */
    private $specification;

    public function setup()
    {
        $this->specification = new PayableAccount();
    }

    /**
     * @test
     */
    public function asset_and_expense_accounts_satisfy_specification()
    {
        $assetAccount = m::mock(Account::class);
        $assetAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('asset'));

        $expenseAccount = m::mock(Account::class);
        $expenseAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('expense'));

        $this->specification->isSatisfiedBy($assetAccount, $expenseAccount);
    }

    /**
     * @test
     */
    public function expense_and_asset_accounts_do_not_satisfy_specification()
    {
        $expenseAccount = m::mock(Account::class);
        $expenseAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('expense'));

        $assetAccount = m::mock(Account::class);
        $assetAccount->shouldReceive('type')
            ->never();

        $this->specification->isSatisfiedBy($expenseAccount, $assetAccount);
    }
}
