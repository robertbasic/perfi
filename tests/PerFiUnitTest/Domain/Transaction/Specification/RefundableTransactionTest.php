<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Specification;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\Specification\RefundableTransaction;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class RefundableTransactionTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use TransactionTrait;

    /**
     * @var Account
     */
    private $assetAccount;

    /**
     * @var Account
     */
    private $expenseAccount;

    /**
     * @var TransactionType
     */
    private $payTransactionType;

    /**
     * @var Transaction
     */
    private $refundTransactionType;

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
        $this->payTransactionType = $this->pay();
        $this->refundTransactionType = $this->refund();

        $this->assetAccount = $this->mockAccount('asset');

        $this->expenseAccount = $this->mockAccount('expense');

        $this->transaction = $this->mockTransaction();

        $this->specification = new RefundableTransaction();
    }

    /**
     * @test
     */
    public function refund_type_transaction_with_expense_and_asset_accounts_satisfies_specification()
    {
        $this->transaction->shouldReceive('type')
            ->once()
            ->andReturn($this->refundTransactionType);
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
        $this->transaction->shouldReceive('type')
            ->once()
            ->andReturn($this->refundTransactionType);
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

        $this->transaction->shouldReceive('type')
            ->once()
            ->andReturn($this->payTransactionType);
        $this->transaction->shouldReceive('sourceAccount')
            ->never();
        $this->transaction->shouldReceive('destinationAccount')
            ->never();

        $result = $this->specification->isSatisfiedBy($this->transaction);

        self::assertFalse($result);
    }
}
