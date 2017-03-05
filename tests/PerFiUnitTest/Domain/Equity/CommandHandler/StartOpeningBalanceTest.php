<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Equity\CommandHandler;

use PHPUnit\Framework\TestCase;
use PerFi\Application\Equity\InMemoryOpeningBalanceRepository;
use PerFi\Domain\Equity\CommandHandler\StartOpeningBalance;
use PerFi\Domain\Equity\Command\StartOpeningBalance as StartOpeningBalanceCommand;
use PerFi\Domain\MoneyFactory;

class StartOpeningBalanceTest extends TestCase
{
    /**
     * @test
     */
    public function when_invoked_it_adds_the_opening_balance_to_the_totals()
    {
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');

        $repository = new InMemoryOpeningBalanceRepository();

        $commandHandler = new StartOpeningBalance($repository);

        $command = new StartOpeningBalanceCommand($amount);

        $commandHandler->__invoke($command);

        $result = $repository->getAll();

        $expected = 1;

        self::assertSame($expected, count($result));
    }
}
