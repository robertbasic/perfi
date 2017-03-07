<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\MoneyFactory;

class AccountTest extends TestCase
{
    /**
     * @test
     */
    public function account_can_be_created_by_type_with_title()
    {
        $type = 'asset';
        $title = 'Cash';

        $account = Account::byStringType($type, $title);

        self::assertSame('Cash, asset', (string) $account);
        self::assertInstanceOf(AccountId::class, $account->id());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function account_cannot_be_created_with_empty_title()
    {
        $type = 'asset';
        $title = '';

        $account = Account::byStringType($type, $title);
    }

    /**
     * @test
     */
    public function empty_balances_when_nothing_was_credited_or_debited()
    {
        $type = 'asset';
        $title = 'Cash';

        $account = Account::byStringType($type, $title);

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

        $account = Account::byStringType('asset', 'Cash');

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

        $account = Account::byStringType('asset', 'Cash');

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
}
