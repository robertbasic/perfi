Feature: Opening balances as equity
    In order track my personal finances
    I should be able to set opening balances as equity
    In multiple currencies

    These opening balances will be used to create assets of any kind

    Scenario: Starting a new opening balance for a single equity
        Given I have equity of 1300500 RSD
        When I start a new opening balance for RSD
        Then I should have an opening balance of 1300500 RSD

    Scenario: Starting a new opening balance for multiple equities in the same currency
        Given I have equity of 1000000 RSD
        And I have equity of 300500 RSD
        When I start a new opening balance for RSD
        Then I should have an opening balance of 1300500 RSD

#     Scenario: Starting new opening balances for single equities in different currencies
#         Given I have equity of 1300500 currency RSD
#         And I have equity of 99988 currency EUR
#         When I start a new opening balance for currency RSD
#         And I start a new opening balance for currency EUR
#         Then I should have an opening balance of 1300500 currency RSD
#         And I should have an opening balance of 99988 currency EUR
