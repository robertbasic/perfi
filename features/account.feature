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

    Scenario: Adding a new expense account
        Given I want to add a new expense account "Groceries"
        When I add the new account
        Then I should have an expense account called "Groceries"

    Scenario: Adding a new income account
        Given I want to add a new income account "Salaries"
        When I add the new account
        Then I should have an income account called "Salaries"
