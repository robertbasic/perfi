<?php
declare(strict_types=1);

namespace PerFiUnitTest\Application\Account;

use PHPUnit\Framework\TestCase;
use PerFi\Application\Account\InMemoryAccountRepository;
use PerFi\Domain\Account\Account;

class InMemoryAccountRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function can_add_account_to_repository()
    {
        $type = 'asset';
        $title = 'Cash';

        $account = Account::byStringType($type, $title);

        $repository = new InMemoryAccountRepository();

        $repository->add($account);

        $accounts = $repository->getAll();

        foreach ($accounts as $id => $result) {
            self::assertSame((string) $account->id(), $id);
            self::assertSame($result, $account);
        }
    }
}
