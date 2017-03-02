<?php
declare(strict_types=1);

namespace PerFiFeatureTest\Equity;

use Behat\Behat\Context\Context;
use PerFi\Application\Equity\InMemoryOpeningBalanceRepository;
use PerFi\Domain\Command;
use PerFi\Domain\CommandHandler;
use PerFi\Domain\Equity\CommandHandler\StartOpeningBalance as StartOpeningBalanceCommandHandler;
use PerFi\Domain\Equity\Command\StartOpeningBalance as StartOpeningBalanceCommand;
use PerFi\Domain\Equity\OpeningBalanceRepository;
use PerFi\Domain\MoneyFactory;
use Webmozart\Assert\Assert;

class OpeningBalanceContext implements Context
{
    /**
     * @var Command[]
     */
    private $startOpeningBalanceCommands;

    /**
     * @var CommandHandler
     */
    private $startOpeningBalanceHandler;

    /**
     * @var OpeningBalanceRepository
     */
    private $openingBalanceRepository;

    /** @BeforeScenario */
    public function setup()
    {
        $this->startOpeningBalanceCommands = [];

        $this->openingBalanceRepository = new InMemoryOpeningBalanceRepository();
        $this->startOpeningBalanceHandler = new StartOpeningBalanceCommandHandler(
            $this->openingBalanceRepository
        );
    }

    /**
     * @Given I have equity of :amount :currency
     */
    public function iHaveEquityOf($amount, $currency)
    {
        $this->startOpeningBalanceCommands[] = new StartOpeningBalanceCommand($amount, $currency);
    }

    /**
     * @When I start a new opening balance for :currency
     */
    public function iStartANewOpeningBalanceFor($currency)
    {
        foreach ($this->startOpeningBalanceCommands as $command) {
            $this->startOpeningBalanceHandler->__invoke($command);
        }

        $this->startOpeningBalanceCommands = [];
    }

    /**
     * @Then I should have an opening balance of :amount :currency
     */
    public function iShouldHaveAnOpeningBalanceOf($amount, $currency)
    {
        $expected = MoneyFactory::amountInCurrency($amount, $currency);

        $totals = $this->openingBalanceRepository->getTotals();

        foreach ($totals as $result) {
            if ((string) $result->getCurrency() === $currency) {
                Assert::same($result->getAmount(), $expected->getAmount());
                Assert::same((string) $result->getCurrency(), (string) $expected->getCurrency());
            }
        }
    }
}
