<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Specification;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Transaction\Specification\ExecutableTransaction;
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
        $this->markTestSkipped();
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
    public function refundable_transaction_satisfies_specification()
    {
        $this->markTestSkipped();
        $expenseAccount = m::mock(Account::class);
        $expenseAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('expense'));
        $assetAccount = m::mock(Account::class);
        $assetAccount->shouldReceive('type')
            ->once()
            ->andReturn(AccountType::fromString('asset'));

        $transactionType = TransactionType::fromString('refund');

        $result = $this->specification->isSatisfiedBy($transactionType, $expenseAccount, $assetAccount);

        self::assertTrue($result);
    }
}
