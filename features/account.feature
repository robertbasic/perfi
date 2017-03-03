Feature: Accounts
    Accounts hold funds.
    There are several account types:
        - Assets
        - Expenses
        - Income

    Scenario: Adding a new asset account
        Given I want to add a new asset account "Cash"
        When I add the new account
        Then I should have an asset account called "Cash"
