<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\Event;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\AccountTrait;
use PerFi\Domain\Account\Event\AccountBalanceChanged;

class AccountBalanceChangedTest extends TestCase
{
    use AccountTrait;

    /**
     * @test
     */
    public function account_is_set_on_event()
    {
        $assetAccount = $this->assetAccount();

        $event = new AccountBalanceChanged($assetAccount);

        $result = $event->account();

        $this->assertSame($assetAccount, $result);
    }
}
