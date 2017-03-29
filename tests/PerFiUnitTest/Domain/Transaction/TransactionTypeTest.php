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
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The transaction type must be provided
     */
    public function transaction_type_cannot_be_created_for_empty_type()
    {
        $type = '';

        $transactionType = TransactionType::fromString($type);
    }

    /**
     * @test
     * @expectedException PerFi\Domain\Transaction\Exception\UnknownTransactionTypeException
     * @expectedExceptionMessage The spam transaction type is unknown
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
                'refund',
            ],
        ];
    }
}
