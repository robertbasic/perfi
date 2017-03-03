<?php
declare(strict_types=1);

namespace PerFiUnitTest\Application\Account;

use PHPUnit\Framework\TestCase;
use PerFi\Application\Account\InMemoryAccountRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;

class InMemoryAccountRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function can_add_account_to_repository()
    {
        $type = AccountType::fromString('asset');
        $title = 'Cash';

        $account = Account::byType($type, $title);

        $repository = new InMemoryAccountRepository();

        $repository->add($account);

        $accounts = $repository->getAll();

        foreach ($accounts as $id => $result) {
            self::assertSame((string) $account->id(), $id);
            self::assertSame($result, $account);
        }
    }
}
