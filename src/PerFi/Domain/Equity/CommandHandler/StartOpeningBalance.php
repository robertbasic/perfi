<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity\CommandHandler;

use PerFi\Domain\Command;
use PerFi\Domain\CommandHandler;
use PerFi\Domain\Equity\OpeningBalance;
use PerFi\Domain\Equity\OpeningBalanceRepository;

class StartOpeningBalance implements CommandHandler
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
     * Add the opening balance that is started to the repository.
     *
     * @param Command $startOpeningBalanceCommand
     */
    public function __invoke(Command $startOpeningBalanceCommand)
    {
        $openingBalance = $startOpeningBalanceCommand->payload();

        $this->openingBalances->add($openingBalance);
    }
}
