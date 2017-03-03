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

    public function __construct(OpeningBalanceRepository $openingBalances)
    {
        $this->openingBalances = $openingBalances;
    }

    public function __invoke(Command $startOpeningBalanceCommand)
    {
        $openingBalance = $startOpeningBalanceCommand->payload();

        $this->openingBalances->add($openingBalance);
    }
}
