Feature: Transactions
    In order to transfer funds between accounts
    I should be able to create transactions
    Between two accounts

    An asset can pay an expense
    An asset can be funded by an income

    An expense can be refunded to an asset

    An income can fund an asset

    A source of a transaction decreases it's ballance
    A destination of a transaction increases it's ballance

    Scenario: A payment from an asset for an expense
        Given I have an asset account called "Cash"
        And I have an expense account called "Groceries"
        When 1200 RSD is payed for "supermarket groceries" from "Cash" to "Groceries" on "2017-03-12"
        Then there should be a "pay" transaction that happened on "2017-03-12" for 1200 RSD between "Cash" asset account and "Groceries" expense account

    Scenario: A refund for an expense to an asset
        Given I have an asset account called "Cash"
        And I have an expense account called "Groceries"
        When I refund 200 RSD from the "Groceries" to "Cash" on "2017-03-12"
        Then there should be a "refund" transaction that happened on "today" for 200 RSD between "Groceries" expense account and "Cash" asset account
