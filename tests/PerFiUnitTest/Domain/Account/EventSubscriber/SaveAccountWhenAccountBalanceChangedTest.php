<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\AccountTrait;
use PerFiUnitTest\Traits\AmountTrait;
use PerFi\Application\Repository\InMemoryAccountRepository;
use PerFi\Domain\Account\EventSubscriber\SaveAccountWhenAccountBalanceChanged;
use PerFi\Domain\Account\Event\AccountBalanceChanged;

class SaveAccountWhenAccountBalanceChangedTest extends TestCase
{
    use AccountTrait;
    use AmountTrait;

    /**
     * @test
     */
    public function account_is_saved_when_account_balance_is_changed()
    {
        $accountRepository = new InMemoryAccountRepository();

        $amount = $this->amount('500', 'RSD');
        $account = $this->assetAccount();
        $account->debit($amount);

        $event = new AccountBalanceChanged($account);

        $eventSubcriber = new SaveAccountWhenAccountBalanceChanged($accountRepository);
        $eventSubcriber->__invoke($event);

        $account = $accountRepository->get($account->id());

        $result = $account->balances();

        self::assertNotEmpty($result);
    }
}
