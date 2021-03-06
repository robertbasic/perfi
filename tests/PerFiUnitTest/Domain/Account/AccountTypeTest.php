<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Account\Exception\UnknownAccountTypeException;

class AccountTypeTest extends TestCase
{
    /**
     * @test
     * @dataProvider validTypes
     */
    public function account_type_can_be_created_for_valid_types($type)
    {
        $accountType = AccountType::fromString($type);

        self::assertSame($type, (string) $accountType);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The account type must be provided
     */
    public function account_type_cannot_be_created_for_empty_type()
    {
        $type = '';

        $accountType = AccountType::fromString($type);
    }

    /**
     * @test
     * @expectedException PerFi\Domain\Account\Exception\UnknownAccountTypeException
     * @expectedExceptionMessage The spam account type is unknown
     */
    public function account_type_cannot_be_created_for_unknown_type()
    {
        $type = 'spam';

        $accountType = AccountType::fromString($type);
    }

    public function validTypes()
    {
        return [
            [
                'asset',
            ],
            [
                'expense',
            ],
            [
                'income',
            ],
        ];
    }
}
