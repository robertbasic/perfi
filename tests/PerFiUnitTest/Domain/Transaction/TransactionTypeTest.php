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
        $transasctionType = TransactionType::fromString($type);

        self::assertSame($type, (string) $transasctionType);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function transasction_type_cannot_be_created_for_empty_type()
    {
        $type = '';

        $transasctionType = TransactionType::fromString($type);
    }

    /**
     * @test
     * @expectedException PerFi\Domain\Transaction\Exception\UnknownTransactionTypeException
     */
    public function transasction_type_cannot_be_created_for_unknown_type()
    {
        $type = 'spam';

        $transasctionType = TransactionType::fromString($type);
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
