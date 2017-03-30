<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Specification;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Transaction\Specification\NotRefundedTransaction;
use PerFi\Domain\Transaction\Transaction;

class NotRefundedTransactionTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use TransactionTrait;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var NotRefundedTransaction
     */
    private $specification;

    public function setup()
    {
        $this->transaction = $this->mockTransaction();

        $this->specification = new NotRefundedTransaction();
    }

    /**
     * @test
     */
    public function not_refunded_transaction()
    {
        $this->transaction->shouldReceive('refunded')
            ->once()
            ->andReturn(false);

        $result = $this->specification->isSatisfiedBy($this->transaction);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function refunded_transaction()
    {
        $this->transaction->shouldReceive('refunded')
            ->once()
            ->andReturn(true);

        $result = $this->specification->isSatisfiedBy($this->transaction);

        self::assertFalse($result);
    }
}
