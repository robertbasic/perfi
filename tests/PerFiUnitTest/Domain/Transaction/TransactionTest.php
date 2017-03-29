<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction;

use Money\Money;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionId;
use PerFi\Domain\Transaction\TransactionRecordDate;
use PerFi\Domain\Transaction\TransactionType;

class TransactionTest extends TestCase
{
    /**
     * @var TransactionId
     */
    private $id;

    /**
     * @var TransactionType
     */
    private $type;

    /**
     * @var Account
     */
    private $asset;

    /**
     * @var Account
     */
    private $expense;

    /**
     * @var Money
     */
    private $amount;

    /**
     * @var TransactionDate
     */
    private $date;

    /**
     * @var string
     */
    private $description;

    /**
     * @var TransactionRecordDate
     */
    private $recordDate;

    public function setup()
    {
        $this->id = TransactionId::fromString('fddf4716-6c0e-4f54-b539-d2d480a50d1a');

        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');

        $this->type = TransactionType::fromString('pay');
        $this->asset = Account::byTypeWithTitle($asset, 'Cash');
        $this->expense = Account::byTypeWithTitle($expense, 'Groceries');
        $this->amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $this->date = TransactionDate::fromString('2017-03-12');
        $this->description = 'groceries for dinner';
        $this->recordDate = TransactionRecordDate::fromString('2017-03-17 11:29:00');

    }

    /**
     * @test
     */
    public function transaction_between_two_accounts_can_be_created()
    {
        $transaction = Transaction::betweenAccounts(
            $this->type,
            $this->asset,
            $this->expense,
            $this->amount,
            $this->date,
            $this->description
        );

        self::assertInstanceOf(TransactionId::class, $transaction->id());
        self::assertInstanceOf(TransactionRecordDate::class, $transaction->recordDate());
        self::assertSame($this->description, $transaction->description());

        self::assertInstanceOf(TransactionDate::class, $transaction->date());
        self::assertSame('2017-03-12', (string) $transaction->date());

        self::assertInstanceOf(TransactionType::class, $transaction->type());

        self::assertInstanceOf(Account::class, $transaction->sourceAccount());
        self::assertSame($this->asset->id(), $transaction->sourceAccount()->id());

        self::assertInstanceOf(Account::class, $transaction->destinationAccount());
        self::assertSame($this->expense->id(), $transaction->destinationAccount()->id());

        self::assertInstanceOf(Money::class, $transaction->amount());
        self::assertSame($this->amount->getAmount(), $transaction->amount()->getAmount());
        self::assertSame($this->amount->getCurrency(), $transaction->amount()->getCurrency());

        self::assertFalse($transaction->refunded());
    }

    /**
     * @test
     */
    public function transaction_can_be_created_with_an_id()
    {
        $transaction = Transaction::withId(
            $this->id,
            $this->type,
            $this->asset,
            $this->expense,
            $this->amount,
            $this->date,
            $this->recordDate,
            $this->description,
            false
        );

        self::assertSame($this->id, $transaction->id());
    }

    /**
     * @test
     */
    public function transaction_can_be_serialized_to_json()
    {
        $transaction = Transaction::withId(
            $this->id,
            $this->type,
            $this->asset,
            $this->expense,
            $this->amount,
            $this->date,
            $this->recordDate,
            $this->description,
            false
        );

        $expected = [
            'id' => 'fddf4716-6c0e-4f54-b539-d2d480a50d1a',
            'type' => 'pay',
            'source_account' => 'Cash, asset',
            'destination_account' => 'Groceries, expense',
            'amount' => '500.00 RSD',
            'date' => '2017-03-12',
            'description' => 'groceries for dinner',
            'refundable' => true,
        ];

        self::assertSame($expected, $transaction->jsonSerialize());
    }

    /**
     * @test
     */
    public function not_refundable_already_refunded_transaction_can_be_serialized_to_json()
    {
        $transaction = Transaction::withId(
            $this->id,
            $this->type,
            $this->asset,
            $this->expense,
            $this->amount,
            $this->date,
            $this->recordDate,
            $this->description,
            true
        );

        $expected = [
            'id' => 'fddf4716-6c0e-4f54-b539-d2d480a50d1a',
            'type' => 'pay',
            'source_account' => 'Cash, asset',
            'destination_account' => 'Groceries, expense',
            'amount' => '500.00 RSD',
            'date' => '2017-03-12',
            'description' => 'groceries for dinner',
            'refundable' => false,
        ];

        self::assertSame($expected, $transaction->jsonSerialize());
    }

    /**
     * @test
     */
    public function not_refundable_refund_transaction_can_be_serialized_to_json()
    {
        $type = TransactionType::fromString('refund');

        $transaction = Transaction::withId(
            $this->id,
            $type,
            $this->expense,
            $this->asset,
            $this->amount,
            $this->date,
            $this->recordDate,
            $this->description,
            false
        );

        $expected = [
            'id' => 'fddf4716-6c0e-4f54-b539-d2d480a50d1a',
            'type' => 'refund',
            'source_account' => 'Groceries, expense',
            'destination_account' => 'Cash, asset',
            'amount' => '500.00 RSD',
            'date' => '2017-03-12',
            'description' => 'groceries for dinner',
            'refundable' => false,
        ];

        self::assertSame($expected, $transaction->jsonSerialize());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage The transaction description must be provided
     */
    public function transaction_can_not_be_created_without_a_description()
    {
        $transaction = Transaction::betweenAccounts(
            $this->type,
            $this->asset,
            $this->expense,
            $this->amount,
            $this->date,
            ''
        );
    }

    /**
     * @test
     */
    public function credits_source_account()
    {
        $transaction = Transaction::betweenAccounts(
            $this->type,
            $this->asset,
            $this->expense,
            $this->amount,
            $this->date,
            $this->description
        );

        $transaction->creditSourceAccount();

        $balances = $this->asset->balances();

        self::assertNotEmpty($balances);
    }

    /**
     * @test
     */
    public function debits_destination_account()
    {
        $transaction = Transaction::betweenAccounts(
            $this->type,
            $this->asset,
            $this->expense,
            $this->amount,
            $this->date,
            $this->description
        );

        $transaction->debitDestinationAccount();

        $balances = $this->expense->balances();

        self::assertNotEmpty($balances);
    }

    /**
     * @test
     * @dataProvider assetAccountAndExpenseAccount
     */
    public function pay_transaction_can_be_executed_between_asset_and_expense($asset, $expense)
    {
        $type = TransactionType::fromString('pay');
        $transaction = Transaction::betweenAccounts(
            $type,
            $asset,
            $expense,
            $this->amount,
            $this->date,
            $this->description
        );

        self::assertInstanceOf(Transaction::class, $transaction);
    }

    /**
     * @test
     * @dataProvider assetAccountAndExpenseAccount
     */
    public function refund_transaction_can_be_executed_between_expense_and_asset($asset, $expense)
    {
        $type = TransactionType::fromString('refund');
        $transaction = Transaction::betweenAccounts(
            $type,
            $expense,
            $asset,
            $this->amount,
            $this->date,
            $this->description
        );

        self::assertInstanceOf(Transaction::class, $transaction);
    }

    public function assetAccountAndExpenseAccount()
    {
        $assetType = AccountType::fromString('asset');
        $asset = Account::byTypeWithTitle($assetType, 'Cash');

        $expenseType = AccountType::fromString('expense');
        $expense = Account::byTypeWithTitle($expenseType, 'Groceries');

        return [
            [
                $asset,
                $expense,
            ],
        ];
    }
}
