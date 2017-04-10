<?php
declare(strict_types=1);

namespace PerFiIntegrationTest;

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
        $connection->query("TRUNCATE TABLE `account`");
        $connection->query("TRUNCATE TABLE `balance`");
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
        $this->visitPath('/pay');
        $this->selectOption('pay_source', $destination);
        $this->selectOption('pay_destination', $source);
        $this->fillField('pay_amount', $amount);
        $this->selectOption('pay_currency', $currency);
        $this->fillField('pay_date', $date);
        $this->fillField('pay_description', 'test');
        $submit = $this->getSession()->getPage()->find("css", ".btn-primary");
        $submit->click();

        $this->visitPath('/transactions');
        $this->getSession()->wait(10000);
        $submit = $this->getSession()->getPage()->find("css", ".btn-danger");
        $submit->click();
    }

    /**
     * @Then there should be a ":transactionType" transaction that happened on ":date" for :amount :currency between ":sourceTitle" :sourceType account and ":destinationTitle" :destinationType account
     */
    public function thereShouldBeATransactionOnDateForAmountInCurrencyBetweenAccounts($transactionType, $date, $amount, $currency, $sourceTitle, $sourceType, $destinationTitle, $destinationType)
    {
        $this->visitPath('/transactions');
        $this->getSession()->wait(10000);
        $this->assertPageContainsText($transactionType);
    }
}
