<?php
declare(strict_types=1);

namespace PerFiIntegrationTest;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\MinkExtension\Context\MinkContext;
use Symfony\Component\HttpKernel\KernelInterface;

class AccountContext extends MinkContext
{

    /**
     * @var
     */
    private $container;

    public function __construct(KernelInterface $kernel)
    {
        $this->container = $kernel->getContainer();
    }

    /**
     * @Given I want to add a new :type account ":title"
     */
    public function iWantToCreateANewAccount($type, $title)
    {
        $this->visitPath('/create-account');
        $this->selectOption('create_account_type', $type);
        $this->fillField('create_account_title', $title);
    }

    /**
     * @When I add the new account
     */
    public function iAddTheNewAccount()
    {
        $submit = $this->getSession()->getPage()->find("css", ".btn-primary");
        $submit->click();
    }

    /**
     * @Then I should have an :type account called ":title"
     */
    public function iShouldHaveAnAccountCalled($type, $title)
    {
        $this->visitPath('/accounts');
        $this->getSession()->wait(5000, "document.getElementById('accounts-table-".$type."').innerHTML != ''");
        $this->assertPageContainsText('Cash');
    }
}
