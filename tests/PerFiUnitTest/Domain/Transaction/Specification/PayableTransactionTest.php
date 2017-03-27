<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Specification;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Transaction\Specification\PayableTransaction;
use PerFi\Domain\Transaction\TransactionType;

class PayableTransactionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var PayableTransaction
     */
    private $specification;

    public function setup()
    {
        $this->specification = new PayableTransaction();
    }

    /**
     * @test
     */
    public function pay_type_transaction_with_asset_and_expense_accounts_satisfies_specification()
    {
        $assetAccount = m::mock(Account::class);
        $assetAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('asset'));
        $expenseAccount = m::mock(Account::class);
        $expenseAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('expense'));

        $transactionType = TransactionType::fromString('pay');

        $result = $this->specification->isSatisfiedBy($transactionType, $assetAccount, $expenseAccount);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function pay_type_transaction_with_expense_and_asset_accounts_does_not_satisfy_specification()
    {
        $expenseAccount = m::mock(Account::class);
        $expenseAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('expense'));
        $assetAccount = m::mock(Account::class);
        $assetAccount->shouldReceive('type')
            ->never();

        $transactionType = TransactionType::fromString('pay');

        $result = $this->specification->isSatisfiedBy($transactionType, $expenseAccount, $assetAccount);

        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function refund_type_transaction_with_asset_and_expense_accounts_does_not_satisfy_specification()
    {
        $assetAccount = m::mock(Account::class);
        $assetAccount->shouldReceive('type')
            ->never();
        $expenseAccount = m::mock(Account::class);
        $expenseAccount->shouldReceive('type')
            ->never();

        $transactionType = TransactionType::fromString('refund');

        $result = $this->specification->isSatisfiedBy($transactionType, $assetAccount, $expenseAccount);

        self::assertFalse($result);
    }
}
