<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;

class AccountTest extends TestCase
{

    /**
     * @var AccountType
     */
    private $type;

    /**
     * @var string
     */
    private $title;

    public function setup()
    {
        $this->type = AccountType::fromString('asset');
        $this->title = 'Cash';
    }

    /**
     * @test
     */
    public function account_can_be_created_by_type_with_title()
    {
        $account = Account::byTypeWithTitle($this->type, $this->title);

        self::assertSame('Cash, asset', (string) $account);
        self::assertInstanceOf(AccountId::class, $account->id());
    }

    /**
     * @test
     */
    public function account_can_be_created_with_an_id()
    {
        $id = AccountId::fromString('fddf4716-6c0e-4f54-b539-d2d480a50d1a');

        $account = Account::withId($id, $this->type, $this->title);

        self::assertSame('Cash, asset', (string) $account);
        self::assertSame($id, $account->id());
    }

    /**
     * @test
     */
    public function account_can_be_serialized_to_json()
    {
        $id = AccountId::fromString('fddf4716-6c0e-4f54-b539-d2d480a50d1a');

        $account = Account::withId($id, $this->type, $this->title);

        $expected = [
            'id' => 'fddf4716-6c0e-4f54-b539-d2d480a50d1a',
            'title' => 'Cash',
            'type' => 'asset',
        ];

        self::assertSame($expected, $account->jsonSerialize());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function account_cannot_be_created_with_empty_title()
    {
        $title = '';

        $account = Account::byTypeWithTitle($this->type, $title);
    }

    /**
     * @test
     */
    public function empty_balances_when_nothing_was_credited_or_debited()
    {
        $account = Account::byTypeWithTitle($this->type, $this->title);

        $balances = $account->balances();

        self::assertEmpty($balances);
    }

    /**
     * @test
     */
    public function crediting_an_account_adds_negative_amount()
    {
        $amountOne = MoneyFactory::amountInCurrency('500', 'RSD');
        $amountTwo = MoneyFactory::amountInCurrency('600', 'RSD');

        $account = Account::byTypeWithTitle($this->type, 'Cash');

        $account->credit($amountOne);
        $account->credit($amountTwo);

        $results = $account->balances();

        $expected = [
            'RSD' => '-1100',
        ];

        foreach ($results as $currency => $result) {
            $expectedBalance = MoneyFactory::amountInCurrency($expected[$currency], $currency);

            self::assertTrue($expectedBalance->equals($result));
        }
    }

    /**
     * @test
     */
    public function debiting_an_account_adds_positive_amount()
    {
        $amountOne = MoneyFactory::amountInCurrency('500', 'RSD');
        $amountTwo = MoneyFactory::amountInCurrency('600', 'RSD');

        $account = Account::byTypeWithTitle($this->type, 'Cash');

        $account->debit($amountOne);
        $account->debit($amountTwo);

        $results = $account->balances();

        $expected = [
            'RSD' => '1100',
        ];

        foreach ($results as $currency => $result) {
            $expectedBalance = MoneyFactory::amountInCurrency($expected[$currency], $currency);

            self::assertTrue($expectedBalance->equals($result));
        }
    }

    /**
     * @test
     * @dataProvider assetAndExpenseAccounts
     */
    public function asset_account_can_pay_expense_account($asset, $expense)
    {
        self::assertTrue($asset->canPay($expense));
        self::assertFalse($expense->canPay($asset));
    }

    /**
     * @test
     * @dataProvider assetAndExpenseAccounts
     */
    public function asset_account_can_refund_expense_account($asset, $expense)
    {
        self::assertTrue($asset->canRefund($expense));
        self::assertFalse($expense->canRefund($asset));
    }

    /**
     * @test
     * @dataProvider assetAndExpenseAccounts
     */
    public function expense_account_can_charge_asset_account($asset, $expense)
    {
        self::assertTrue($expense->canCharge($asset));
        self::assertFalse($asset->canCharge($expense));
    }

    /**
     * @test
     * @dataProvider assetAndExpenseAccounts
     */
    public function expense_account_can_pay_back_asset_account($asset, $expense)
    {
        self::assertTrue($expense->canPayBack($asset));
        self::assertFalse($asset->canPayBack($expense));
    }

    public function assetAndExpenseAccounts()
    {
        $assetType = AccountType::fromString('asset');
        $asset = Account::byTypeWithTitle($assetType, 'Cash');

        $expenseType = AccountType::fromString('expense');
        $expense = Account::byTypeWithTitle($expenseType, 'Cash');

        return [
            [
                $asset,
                $expense
            ],
        ];
    }
}
