<?php
declare(strict_types=1);

namespace PerFiFeatureTest\Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use PerFi\Application\Account\InMemoryAccountRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\CommandHandler\CreateAccount as CreateAccountCommandHandler;
use PerFi\Domain\Account\Command\CreateAccount as CreateAccountCommand;
use Webmozart\Assert\Assert;

class AccountContext implements Context
{
    /**
     * @var Command
     */
    private $createAccountCommand;

    /**
     * @var CommandHandler
     */
    private $createAccountHandler;

    /**
     * @var AccountRepository
     */
    private $repository;


    /** @BeforeScenario */
    public function setup()
    {
        $this->repository = new InMemoryAccountRepository();
        $this->createAccountHandler = new CreateAccountCommandHandler(
            $this->repository
        );
    }

    /**
     * @Given I want to add a new :type account ":title"
     */
    public function iWantToCreateANewAccount($type, $title)
    {
        $this->createAccountCommand = new CreateAccountCommand($type, $title);
    }

    /**
     * @When I add the new account
     */
    public function iCreateIt()
    {
        $this->createAccountHandler->__invoke($this->createAccountCommand);
    }

    /**
     * @Then I should have an :type account called ":title"
     */
    public function iShouldHaveAnAccountCalled($type, $title)
    {
        $accounts = $this->repository->getAll();

        foreach ($accounts as $account) {
            Assert::isInstanceOf($account, Account::class);
        }
    }

}
