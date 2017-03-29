<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account;

use Money\Money;
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

    /**
     * @var Money
     */
    private $amountFiveHundred;

    /**
     * @var Money
     */
    private $amountSixHundred;

    public function setup()
    {
        $this->type = AccountType::fromString('asset');
        $this->title = 'Cash';
        $this->amountFiveHundred = MoneyFactory::amountInCurrency('500', 'RSD');
        $this->amountSixHundred = MoneyFactory::amountInCurrency('600', 'RSD');
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
     * @expectedExceptionMessage The account title must be provided
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
        $account = Account::byTypeWithTitle($this->type, 'Cash');

        $account->credit($this->amountFiveHundred);
        $account->credit($this->amountSixHundred);

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

        $account = Account::byTypeWithTitle($this->type, 'Cash');

        $account->debit($this->amountFiveHundred);
        $account->debit($this->amountSixHundred);

        $results = $account->balances();

        $expected = [
            'RSD' => '1100',
        ];

        foreach ($results as $currency => $result) {
            $expectedBalance = MoneyFactory::amountInCurrency($expected[$currency], $currency);

            self::assertTrue($expectedBalance->equals($result));
        }
    }
}
