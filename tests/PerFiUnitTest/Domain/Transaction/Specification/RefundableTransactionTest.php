<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Specification;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Transaction\Specification\RefundableTransaction;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class RefundableTransactionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var RefundableTransaction
     */
    private $specification;

    public function setup()
    {
        $this->specification = new RefundableTransaction();
    }

    /**
     * @test
     */
    public function refund_type_transaction_with_expense_and_asset_accounts_satisfies_specification()
    {
        $expenseAccount = m::mock(Account::class);
        $expenseAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('expense'));
        $assetAccount = m::mock(Account::class);
        $assetAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('asset'));

        $transaction = m::mock(Transaction::class);
        $transaction->shouldReceive('type')
            ->once()
            ->andReturn(TransactionType::fromString('refund'));
        $transaction->shouldReceive('sourceAccount')
            ->once()
            ->andReturn($expenseAccount);
        $transaction->shouldReceive('destinationAccount')
            ->once()
            ->andReturn($assetAccount);

        $result = $this->specification->isSatisfiedBy($transaction);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function refund_type_transaction_with_asset_and_expense_accounts_does_not_satisfy_specification()
    {
        $assetAccount = m::mock(Account::class);
        $assetAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('asset'));
        $expenseAccount = m::mock(Account::class);
        $expenseAccount->shouldReceive('type')
            ->never();

        $transaction = m::mock(Transaction::class);
        $transaction->shouldReceive('type')
            ->once()
            ->andReturn(TransactionType::fromString('refund'));
        $transaction->shouldReceive('sourceAccount')
            ->once()
            ->andReturn($assetAccount);
        $transaction->shouldReceive('destinationAccount')
            ->once()
            ->andReturn($expenseAccount);

        $result = $this->specification->isSatisfiedBy($transaction);

        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function pay_type_transaction_with_expense_and_asset_accounts_does_not_satisfy_specification()
    {
        $transaction = m::mock(Transaction::class);
        $transaction->shouldReceive('type')
            ->once()
            ->andReturn(TransactionType::fromString('pay'));

        $result = $this->specification->isSatisfiedBy($transaction);

        self::assertFalse($result);
    }
}
