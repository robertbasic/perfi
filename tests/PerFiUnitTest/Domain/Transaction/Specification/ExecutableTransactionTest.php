<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Specification;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Transaction\Specification\ExecutableTransaction;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class ExecutableTransactionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function setup()
    {
        $this->specification = new ExecutableTransaction();
    }

    /**
     * @test
     */
    public function payable_transaction_satisfies_specification()
    {
        $assetAccount = m::mock(Account::class);
        $assetAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('asset'));
        $expenseAccount = m::mock(Account::class);
        $expenseAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('expense'));

        $transaction = m::mock(Transaction::class);
        $transaction->shouldReceive('type')
            ->once()
            ->andReturn(TransactionType::fromString('pay'));
        $transaction->shouldReceive('sourceAccount')
            ->once()
            ->andReturn($assetAccount);
        $transaction->shouldReceive('destinationAccount')
            ->once()
            ->andReturn($expenseAccount);

        $result = $this->specification->isSatisfiedBy($transaction);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function refundable_transaction_satisfies_specification()
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
            ->twice()
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
}
