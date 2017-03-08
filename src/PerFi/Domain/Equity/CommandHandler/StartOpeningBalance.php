<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity\CommandHandler;

use Perfi\Domain\Equity\Command\StartOpeningBalance as StartOpeningBalanceCommand;
use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\Equity\OpeningBalanceRepository;

class StartOpeningBalance
{
    /**
     * @var OpeningBalanceRepository
     */
    private $openingBalances;

    /**
     * A handler that handles starting an opening balance
     *
     * @param OpeningBalanceRepository $openingBalances
     */
    public function __construct(OpeningBalanceRepository $openingBalances)
    {
        $this->openingBalances = $openingBalances;
    }

    /**
     * Handle the start opening balance command
     *
     * Start the opening balance and add it to the repository.
     *
     * @param StartOpeningBalanceCommand $command
     */
    public function __invoke(StartOpeningBalanceCommand $command)
    {
        $amount = $command->amount();

        $openingBalance = OpeningBalance::forStarting($amount);

        $this->openingBalances->add($openingBalance);
    }
}
