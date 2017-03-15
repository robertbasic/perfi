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
     */
    public function asset_is_asset()
    {
        $accountType = AccountType::fromString('asset');

        self::assertTrue($accountType->isAsset());
    }

    /**
     * @test
     */
    public function expense_is_expense()
    {
        $accountType = AccountType::fromString('expense');

        self::assertTrue($accountType->isExpense());
    }

    /**
     * @test
     */
    public function income_is_income()
    {
        $accountType = AccountType::fromString('income');

        self::assertTrue($accountType->isIncome());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function account_type_cannot_be_created_for_empty_type()
    {
        $type = '';

        $accountType = AccountType::fromString($type);
    }

    /**
     * @test
     * @expectedException PerFi\Domain\Account\Exception\UnknownAccountTypeException
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
