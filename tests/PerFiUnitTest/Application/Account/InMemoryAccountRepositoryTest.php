<?php
declare(strict_types=1);

namespace PerFiUnitTest\Application\Account;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\AccountTrait;
use PerFi\Application\Account\InMemoryAccountRepository;

class InMemoryAccountRepositoryTest extends TestCase
{
    use AccountTrait;

    /**
     * @test
     */
    public function can_add_account_to_repository()
    {
        $account = $this->assetAccount();

        $repository = new InMemoryAccountRepository();

        $repository->add($account);

        $accounts = $repository->getAll();

        foreach ($accounts as $id => $result) {
            self::assertSame((string) $account->id(), $id);
            self::assertSame($result, $account);

            $result = $repository->get($account->id());

            self::assertSame($result, $account);
        }
    }
}
