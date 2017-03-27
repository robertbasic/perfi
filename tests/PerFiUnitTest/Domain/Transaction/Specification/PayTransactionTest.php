<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Specification;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Transaction\Specification\PayTransaction;
use PerFi\Domain\Transaction\TransactionType;

class PayTransactionTest extends TestCase
{
    /**
     * @var PayTransaction
     */
    private $specification;

    public function setup()
    {
        $this->specification = new PayTransaction();
    }

    /**
     * @test
     */
    public function pay_type_transaction_satisfies_specification()
    {
        $type = TransactionType::fromString('pay');

        $result = $this->specification->isSatisfiedBy($type);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function refund_type_transaction_does_not_satisfy_specification()
    {
        $type = TransactionType::fromString('refund');

        $result = $this->specification->isSatisfiedBy($type);

        self::assertFalse($result);
    }
}
