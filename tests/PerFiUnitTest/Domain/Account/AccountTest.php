<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountType;

class AccountTest extends TestCase
{
    /**
     * @test
     */
    public function account_can_be_created_by_type_with_title()
    {
        $type = AccountType::fromString('asset');
        $title = 'Cash';

        $account = Account::byType($type, $title);

        self::assertSame('Cash, asset', (string) $account);
        self::assertInstanceOf(AccountId::class, $account->id());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function account_cannot_be_created_with_empty_title()
    {
        $type = AccountType::fromString('asset');
        $title = '';

        $account = Account::byType($type, $title);
    }
}
