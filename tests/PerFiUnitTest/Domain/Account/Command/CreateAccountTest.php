<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\Command;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Account\Command\CreateAccount;

class CreateAccountTest extends TestCase
{
    /**
     * @test
     */
    public function account_is_payload()
    {
        $type = 'asset';
        $title = 'Cash';

        $command = new CreateAccount($type, $title);

        $accountType = $command->accountType();
        $title = $command->title();

        self::assertInstanceOf(AccountType::class, $accountType);
        self::assertSame('asset', (string) $accountType);
        self::assertSame('Cash', $title);
    }
}
