Feature: Transactions
    In order to transfer funds between accounts
    I should be able to create transactions
    Between two accounts

    An asset can pay an expense
    An asset can be refunded by an expense
    An asset can be funded by an income

    An expense can be paid by an asset
    An expense can charge an asset
    An expense can pay back an asset

    An income can fund an asset

    An asset payment and an expense charge are each other's inversions.

    An asset refund and an expense pay back are each other's inversions.

    A source of a transaction decreases it's ballance
    A destination of a transaction increases it's ballance

    Scenario: A payment from an asset for an expense
        Given I have an asset account called "Cash"
        And I have an expense account called "Groceries"
        When 1200 RSD is payed for "supermarket groceries" from "Cash" to "Groceries"
        Then I should have 1200 RSD funds less in "Cash" asset account
        And I should have 1200 RSD funds more in "Groceries" expense account

    Scenario: A charge for an expense from an asset
        Given I have an asset account called "Cash"
        And I have an expense account called "Groceries"
        When 1200 RSD is charged for "supermarket groceries" from "Groceries" to "Cash"
        Then I should have 1200 RSD funds less in "Cash" asset account
        And I should have 1200 RSD funds more in "Groceries" expense account

    Scenario: A refund for an expense to an asset
        Given I have executed a transaction between a "Cash" asset and a "Groceries" expense for 1200 RSD
        When I refund 200 RSD for the "Groceries" to "Cash"
        Then I should have 200 RSD total in "Cash" asset account
        And I should have 1000 RSD total in "Groceries" expense account
