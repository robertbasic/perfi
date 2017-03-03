Feature: Transactions
    In order to transfer funds between accounts
    I should be able to create transactions
    Between two accounts

    There are two types of transactions:
        - paying transaction, where the source account
        pays an amount to the destination account
        - charging transaction, where the source account
        charges an amount to the destination account

    These two transactions are each other's inverse transactions.

    Scenario: A paying transaction between two accounts
        Given I have an asset account called "Cash"
        And I have an expense account called "Groceries"
        When 1200 RSD is payed for "supermarket groceries" from "Cash" to "Groceries"
        Then I should have 1200 RSD funds less in "Cash" asset account
        And I should have 1200 RSD funds more in "Groceries" expense account

    Scenario: A charging transaction between two accounts
        Given I have an asset account called "Cash"
        And I have an expense account called "Groceries"
        When 1200 RSD is charged for "supermarket groceries" from "Groceries" to "Cash"
        Then I should have 1200 RSD funds less in "Cash" asset account
        And I should have 1200 RSD funds more in "Groceries" expense account
