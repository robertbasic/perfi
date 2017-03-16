Feature: Transactions
    In order to transfer funds between accounts
    I should be able to create transactions
    Between two accounts

    An asset can pay an expense
    An asset can be funded by an income

    # fix wording (can be refunded to an)
    # fix scenario
    An expense can refund an asset

    An income can fund an asset

    A source of a transaction decreases it's ballance
    A destination of a transaction increases it's ballance

    Scenario: A payment from an asset for an expense
        Given I have an asset account called "Cash"
        And I have an expense account called "Groceries"
        When 1200 RSD is payed for "supermarket groceries" from "Cash" to "Groceries" on "2017-03-12"
        Then I should have 1200 RSD funds less in "Cash" asset account
        And I should have 1200 RSD funds more in "Groceries" expense account
        And the transaction should have happened on "2017-03-12"

    Scenario: A refund for an expense to an asset
        Given I have an asset account called "Cash"
        And I have an expense account called "Groceries"
        When I refund 200 RSD from the "Cash" to "Groceries" on "2017-03-12"
        Then I should have 200 RSD funds more in "Cash" asset account
        And I should have 200 RSD funds less in "Groceries" expense account
        And the transaction should have happened on "2017-03-12"
