<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Specification;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Transaction\Specification\PayTransaction;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class PayTransactionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var PayTransaction
     */
    private $specification;

    public function setup()
    {
        $this->transaction = m::mock(Transaction::class);

        $this->specification = new PayTransaction();
    }

    /**
     * @test
     */
    public function pay_type_transaction_satisfies_specification()
    {
        $this->transaction->shouldReceive('type')
            ->once()
            ->andReturn(TransactionType::fromString('pay'));

        $result = $this->specification->isSatisfiedBy($this->transaction);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function refund_type_transaction_does_not_satisfy_specification()
    {
        $this->transaction->shouldReceive('type')
            ->once()
            ->andReturn(TransactionType::fromString('refund'));

        $result = $this->specification->isSatisfiedBy($this->transaction);

        self::assertFalse($result);
    }
}
