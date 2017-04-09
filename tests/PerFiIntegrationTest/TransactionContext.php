<?php
declare(strict_types=1);

namespace PerFiIntegrationTest;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\HttpKernel\KernelInterface;

class TransactionContext extends MinkContext
{

    /**
     * @var
     */
    private $container;

    public function __construct(KernelInterface $kernel)
    {
        $this->container = $kernel->getContainer();
    }

    /** @AfterScenario */
    public function teardown()
    {
        $connection = $this->container->get('database_connection');
        $connection->query("TRUNCATE TABLE `transaction`");
    }

    /**
     * @Given I have an :type account called :title
     */
    public function iHaveAnAccountOfCertianTypeCalled($type, $title)
    {
        $this->visitPath('/create-account');
        $this->selectOption('create_account_type', $type);
        $this->fillField('create_account_title', $title);
        $submit = $this->getSession()->getPage()->find("css", ".btn-primary");
        $submit->click();
    }

    /**
     * @When :amount :currency is payed for :description from :source to :destination on :date
     */
    public function anAmountInCurrencyIsPayedForSomething($amount, $currency, $description, $source, $destination, $date)
    {
        $this->visitPath('/pay');
        $this->selectOption('pay_source', $source);
        $this->selectOption('pay_destination', $destination);
        $this->fillField('pay_amount', $amount);
        $this->selectOption('pay_currency', $currency);
        $this->fillField('pay_date', $date);
        $this->fillField('pay_description', $description);
        $submit = $this->getSession()->getPage()->find("css", ".btn-primary");
        $submit->click();
    }

    /**
     * @When I refund :amount :currency from the :source to :destination on :date
     */
    public function iRefundAmountInCurrency($amount, $currency, $source, $destination, $date)
    {
        throw new PendingException();
    }

    /**
     * @Then I should have :amount :currency funds less in :title :type account
     */
    public function iShouldHaveLessFundsInSourceAccount($amount, $currency, $title, $type)
    {
        $this->visitPath('/transaction');
        $this->getSession()->wait(10000);
        /* $this->assertPageContainsText($title); */
    }

    /**
     * @Then I should have :amount :currency funds more in :title :type account
     */
    public function iShouldHaveMoreFundsInDestinationAccount($amount, $currency, $title, $type)
    {
        throw new PendingException();
    }

    /**
     * @Then the transaction should have happened on :date
     */
    public function theTransactionShouldHaveHappenedOnDate($date)
    {
        throw new PendingException();
    }
}
