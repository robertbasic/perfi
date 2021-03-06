<?php
declare(strict_types=1);

namespace PerFiUnitTest\Traits;

use Mockery as m;
use PerFiUnitTest\Traits\AccountTypeTrait;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountRepository;

trait AccountTrait
{
    use AccountTypeTrait;

    public function assetAccount() : Account
    {
        return Account::byTypeWithTitle($this->asset(), 'Cash');
    }

    public function expenseAccount() : Account
    {
        return Account::byTypeWithTitle($this->expense(), 'Groceries');
    }

    public function mockAccount($type=null) : Account
    {
        $account = m::mock(Account::class);

        if ($type == 'asset') {
            $account->shouldReceive('id')
                ->andReturn(AccountId::fromString('fddf4716-6c0e-4f54-b539-d2d480a50d1a'))
                ->byDefault();
            $account->shouldReceive('type')
                ->andReturn($this->asset())
                ->byDefault();
            $account->shouldReceive('__toString')
                ->andReturn('Cash, asset')
                ->byDefault();
        } else if ($type == 'expense') {
            $account->shouldReceive('id')
                ->andReturn(AccountId::fromString('fddf4716-6c0e-4f54-b539-d2d480a50d1b'))
                ->byDefault();
            $account->shouldReceive('type')
                ->andReturn($this->expense())
                ->byDefault();
            $account->shouldReceive('__toString')
                ->andReturn('Groceries, expense')
                ->byDefault();
        }

        return $account;
    }

    public function mockAccountRepository(Account ...$accounts) : AccountRepository
    {
        $accountRepository = m::mock(AccountRepository::class);
        $accountRepository->shouldReceive('get')
            ->andReturn(...$accounts);

        return $accountRepository;
    }
}
