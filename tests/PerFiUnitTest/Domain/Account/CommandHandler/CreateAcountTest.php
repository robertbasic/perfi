<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\CommandHandler;

use PHPUnit\Framework\TestCase;
use PerFi\Application\Account\InMemoryAccountRepository;
use PerFi\Domain\Account\CommandHandler\CreateAccount;
use PerFi\Domain\Account\Command\CreateAccount as CreateAccountCommand;

class CreateAccountTest extends TestCase
{
    /**
     * @test
     */
    public function when_invoked_it_adds_the_account_to_the_accounts_repository()
    {
        $type = 'asset';
        $title = 'Cash';

        $command = new CreateAccountCommand($type, $title);

        $repository = new InMemoryAccountRepository();

        $commandHandler = new CreateAccount($repository);

        $commandHandler->__invoke($command);

        $result = $repository->getAll();

        $expected = 1;

        self::assertSame($expected, count($result));
    }
}
