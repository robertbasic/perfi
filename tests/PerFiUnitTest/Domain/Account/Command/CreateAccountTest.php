<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\Command;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
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

        $payload = $command->payload();

        self::assertInstanceOf(Account::class, $payload);
    }
}
