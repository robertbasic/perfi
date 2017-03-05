<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Equity\Command;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Equity\Command\StartOpeningBalance;
use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\MoneyFactory;

class StartOpeningBalanceTest extends TestCase
{
    /**
     * @test
     */
    public function opening_balance_is_payload()
    {
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');

        $command = new StartOpeningBalance($amount);

        $payload = $command->payload();

        self::assertInstanceOf(OpeningBalance::class, $payload);
    }
}
