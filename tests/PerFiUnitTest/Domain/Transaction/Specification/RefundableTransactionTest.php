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
     * @var Account
     */
    private $assetAccount;

    /**
     * @var Account
     */
    private $expenseAccount;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var RefundableTransaction
     */
    private $specification;

    public function setup()
    {
        $this->assetAccount = m::mock(Account::class);

        $this->expenseAccount = m::mock(Account::class);

        $this->transaction = m::mock(Transaction::class);

        $this->specification = new RefundableTransaction();
    }

    /**
     * @test
     */
    public function refund_type_transaction_with_expense_and_asset_accounts_satisfies_specification()
    {
        $this->expenseAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('expense'));
        $this->assetAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('asset'));

        $transactionType = TransactionType::fromString('refund');

        $this->transaction->shouldReceive('type')
            ->once()
            ->andReturn($transactionType);
        $this->transaction->shouldReceive('sourceAccount')
            ->once()
            ->andReturn($this->expenseAccount);
        $this->transaction->shouldReceive('destinationAccount')
            ->once()
            ->andReturn($this->assetAccount);

        $result = $this->specification->isSatisfiedBy($this->transaction);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function refund_type_transaction_with_asset_and_expense_accounts_does_not_satisfy_specification()
    {
        $this->assetAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('asset'));
        $this->expenseAccount->shouldReceive('type')
            ->never();

        $transactionType = TransactionType::fromString('refund');

        $this->transaction->shouldReceive('type')
            ->once()
            ->andReturn($transactionType);
        $this->transaction->shouldReceive('sourceAccount')
            ->once()
            ->andReturn($this->assetAccount);
        $this->transaction->shouldReceive('destinationAccount')
            ->never();

        $result = $this->specification->isSatisfiedBy($this->transaction);

        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function pay_type_transaction_with_expense_and_asset_accounts_does_not_satisfy_specification()
    {
        $this->assetAccount->shouldReceive('type')
            ->never();
        $this->expenseAccount->shouldReceive('type')
            ->never();

        $transactionType = TransactionType::fromString('pay');

        $this->transaction->shouldReceive('type')
            ->once()
            ->andReturn($transactionType);
        $this->transaction->shouldReceive('sourceAccount')
            ->never();
        $this->transaction->shouldReceive('destinationAccount')
            ->never();

        $result = $this->specification->isSatisfiedBy($this->transaction);

        self::assertFalse($result);
    }
}
