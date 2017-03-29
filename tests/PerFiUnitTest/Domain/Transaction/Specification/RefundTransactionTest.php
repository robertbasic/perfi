<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Specification;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Transaction\Specification\RefundTransaction;

class RefundTransactionTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use TransactionTrait;

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
        $transaction = $this->refundTransaction();

        $result = $this->specification->isSatisfiedBy($transaction);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function pay_type_transaction_does_not_satisfy_specification()
    {
        $transaction = $this->payTransaction();

        $result = $this->specification->isSatisfiedBy($transaction);

        self::assertFalse($result);
    }
}
