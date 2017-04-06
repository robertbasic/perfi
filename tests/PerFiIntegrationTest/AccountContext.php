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
        throw new PendingException();
    }

    /**
     * @When I add the new account
     */
    public function iAddTheNewAccount()
    {
        throw new PendingException();
    }

    /**
     * @Then I should have an :type account called ":title"
     */
    public function iShouldHaveAnAccountCalled($type, $title)
    {
        throw new PendingException();
    }
}
