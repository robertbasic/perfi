<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Equity\CommandHandler;

use PHPUnit\Framework\TestCase;
use PerFi\Application\Repository\InMemoryOpeningBalanceRepository;
use PerFi\Domain\Equity\CommandHandler\StartOpeningBalance as StartOpeningBalanceHandler;
use PerFi\Domain\Equity\Command\StartOpeningBalance as StartOpeningBalanceCommand;

class StartOpeningBalanceTest extends TestCase
{
    /**
     * @test
     */
    public function when_invoked_it_adds_the_opening_balance_to_the_totals()
    {
        $amount = '500';
        $currency = 'RSD';

        $command = new StartOpeningBalanceCommand($amount, $currency);

        $repository = new InMemoryOpeningBalanceRepository();

        $commandHandler = new StartOpeningBalanceHandler($repository);

        $commandHandler->__invoke($command);

        $result = $repository->getAll();

        $expected = 1;

        self::assertSame($expected, count($result));
    }
}
