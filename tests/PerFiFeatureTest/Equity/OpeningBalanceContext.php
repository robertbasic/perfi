<?php
declare(strict_types=1);

namespace PerFiFeatureTest\Equity;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use PerFi\Application\Equity\InMemoryOpeningBalanceRepository;
use PerFi\Domain\Command;
use PerFi\Domain\CommandHandler;
use PerFi\Domain\Equity\CommandHandler\StartOpeningBalance as StartOpeningBalanceCommandHandler;
use PerFi\Domain\Equity\Command\StartOpeningBalance as StartOpeningBalanceCommand;
use PerFi\Domain\Equity\OpeningBalanceRepository;
use PerFi\Domain\Repository;
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
    }

    /**
     * @Then I should have an opening balance of :amount :currency
     */
    public function iShouldHaveAnOpeningBalanceOf($amount, $currency)
    {
        $currencies = new ISOCurrencies();
        $moneyParser = new DecimalMoneyParser($currencies);

        $expected = $moneyParser->parse($amount, $currency);

        $result = $this->openingBalanceRepository->getTotal();

        Assert::same($expected->getAmount(), $result->getAmount());
        Assert::same((string) $expected->getCurrency(), (string) $result->getCurrency());
    }
}
