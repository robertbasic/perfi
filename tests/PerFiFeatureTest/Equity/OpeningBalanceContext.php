<?php
declare(strict_types=1);

namespace PerFiFeatureTest\Equity;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

class OpeningBalanceContext implements Context
{
    /**
     * @Given I have equity of :arg1 currency :arg2
     */
    public function iHaveEquityOfCurrency($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @When I start a new opening balance for currency :arg1
     */
    public function iStartANewOpeningBalanceForCurrency($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should have an opening balance of :arg1 currency :arg2
     */
    public function iShouldHaveAnOpeningBalanceOfCurrency($arg1, $arg2)
    {
        throw new PendingException();
    }
}
