<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Transaction\TransactionType;
use PerFi\Domain\Transaction\Exception\UnknownTransactionTypeException;

class TransactionTypeTest extends TestCase
{
    /**
     * @test
     * @dataProvider validTypes
     */
    public function transaction_type_can_be_created_for_valid_types($type)
    {
        $transactionType = TransactionType::fromString($type);

        self::assertSame($type, (string) $transactionType);
    }

    /**
     * @test
     */
    public function pay_type_is_pay()
    {
        $transactionType = TransactionType::fromString('pay');

        self::assertTrue($transactionType->isPay());
    }

    /**
     * @test
     */
    public function charge_type_is_charge()
    {
        $transactionType = TransactionType::fromString('charge');

        self::assertTrue($transactionType->isCharge());
    }

    /**
     * @test
     */
    public function refund_type_is_refund()
    {
        $transactionType = TransactionType::fromString('refund');

        self::assertTrue($transactionType->isRefund());
    }

    /**
     * @test
     */
    public function payback_type_is_payback()
    {
        $transactionType = TransactionType::fromString('payback');

        self::assertTrue($transactionType->isPayBack());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function transaction_type_cannot_be_created_for_empty_type()
    {
        $type = '';

        $transactionType = TransactionType::fromString($type);
    }

    /**
     * @test
     * @expectedException PerFi\Domain\Transaction\Exception\UnknownTransactionTypeException
     */
    public function transaction_type_cannot_be_created_for_unknown_type()
    {
        $type = 'spam';

        $transactionType = TransactionType::fromString($type);
    }

    public function validTypes()
    {
        return [
            [
                'pay',
            ],
            [
                'charge',
            ],
            [
                'refund',
            ],
            [
                'payback',
            ],
        ];
    }
}
