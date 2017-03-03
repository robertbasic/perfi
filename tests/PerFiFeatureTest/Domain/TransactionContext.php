<?php
declare(strict_types=1);

namespace PerFiFeatureTest\Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

class TransactionContext implements Context
{
    /**
     * @Given I have an :type account called :title
     */
    public function iHaveAnAccountOfCertianTypeCalled($type, $title)
    {
        throw new PendingException();
    }

    /**
     * @When :amount :currency is payed for :description from :source to :destination
     */
    public function anAmountInCurrencyIsPayedForSomething($amount, $currency, $description, $source, $destination)
    {
        throw new PendingException();
    }

    /**
     * @When :amount :currency is charged for :description from :source to :destination
     */
    public function anAmountInCurrencyIsChargedForSomething($amount, $currency, $description, $source, $destination)
    {
        throw new PendingException();
    }

    /**
     * @Then I should have :amount :currency funds less in :title :type account
     */
    public function iShouldHaveLessFundsInSourceAccount($amount, $currency, $title, $type)
    {
        throw new PendingException();
    }

    /**
     * @Then I should have :amount :currency funds more in :title :type account
     */
    public function iShouldHaveMoreFundsInDestinationAccount($amount, $currency, $title, $type)
    {
        throw new PendingException();
    }
}
