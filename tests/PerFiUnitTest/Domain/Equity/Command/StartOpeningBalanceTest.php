<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Equity\Command;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Equity\Command\StartOpeningBalance;
use PerFi\Domain\Equity\OpeningBalance;

class StartOpeningBalanceTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created()
    {
        $amount = '500';
        $currency = 'RSD';

        $command = new StartOpeningBalance($amount, $currency);

        $payload = $command->payload();

        self::assertInstanceOf(OpeningBalance::class, $payload);
    }
}
