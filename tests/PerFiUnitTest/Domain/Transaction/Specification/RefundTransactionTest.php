<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Specification;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Transaction\Specification\RefundTransaction;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class RefundTransactionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var RefundTransaction
     */
    private $specification;

    public function setup()
    {
        $this->specification = new RefundTransaction();
    }

    /**
     * @test
     */
    public function refund_type_transaction_satisfies_specification()
    {
        $transaction = m::mock(Transaction::class);
        $transaction->shouldReceive('type')
            ->once()
            ->andReturn(TransactionType::fromString('refund'));

        $result = $this->specification->isSatisfiedBy($transaction);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function pay_type_transaction_does_not_satisfy_specification()
    {
        $transaction = m::mock(Transaction::class);
        $transaction->shouldReceive('type')
            ->once()
            ->andReturn(TransactionType::fromString('pay'));

        $result = $this->specification->isSatisfiedBy($transaction);

        self::assertFalse($result);
    }
}
