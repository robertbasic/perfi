<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction;

use Money\Money;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Exception\NotExecutableTransactionException;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionId;
use PerFi\Domain\Transaction\TransactionRecordDate;
use PerFi\Domain\Transaction\TransactionType;

class TransactionTest extends TestCase
{

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

    public function setup()
    {
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');

        $this->type = TransactionType::fromString('pay');
        $this->asset = Account::byTypeWithTitle($asset, 'Cash');
        $this->expense = Account::byTypeWithTitle($expense, 'Groceries');
        $this->amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $this->date = TransactionDate::fromString('2017-03-12');
        $this->description = 'groceries for dinner';
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
    }

    /**
     * @test
     */
    public function transaction_can_be_created_with_an_id()
    {
        $id = TransactionId::fromString('fddf4716-6c0e-4f54-b539-d2d480a50d1a');

        $recordDate = TransactionRecordDate::fromString('2017-03-17 11:29:00');

        $transaction = Transaction::withId(
            $id,
            $this->type,
            $this->asset,
            $this->expense,
            $this->amount,
            $this->date,
            $recordDate,
            $this->description
        );

        self::assertSame($id, $transaction->id());
    }

    /**
     * @test
     */
    public function transaction_can_be_serialized_to_json()
    {
        $id = TransactionId::fromString('fddf4716-6c0e-4f54-b539-d2d480a50d1a');

        $recordDate = TransactionRecordDate::fromString('2017-03-17 11:29:00');

        $transaction = Transaction::withId(
            $id,
            $this->type,
            $this->asset,
            $this->expense,
            $this->amount,
            $this->date,
            $recordDate,
            $this->description
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
    public function pay_transaction_can_be_refunded()
    {
        $transaction = Transaction::betweenAccounts(
            TransactionType::fromString('pay'),
            $this->asset,
            $this->expense,
            $this->amount,
            $this->date,
            $this->description
        );

        self::assertTrue($transaction->canBeRefunded());
    }

    /**
     * @test
     */
    public function refunded_pay_transaction_can_not_be_refunded()
    {
        $transaction = Transaction::betweenAccounts(
            TransactionType::fromString('pay'),
            $this->asset,
            $this->expense,
            $this->amount,
            $this->date,
            $this->description
        );
        $transaction->markAsRefunded();

        self::assertFalse($transaction->canBeRefunded());
    }
    /**
     * @test
     */
    public function refund_transaction_can_not_be_refunded()
    {
        $transaction = Transaction::betweenAccounts(
            TransactionType::fromString('refund'),
            $this->expense,
            $this->asset,
            $this->amount,
            $this->date,
            $this->description
        );

        self::assertFalse($transaction->canBeRefunded());
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
     * @expectedException PerFi\Domain\Transaction\Exception\NotExecutableTransactionException
     * @expectedExceptionMessage The pay transaction cannot be executed between Groceries, expense and Cash, asset accounts
     */
    public function pay_transaction_can_not_be_executed_between_expense_and_asset($asset, $expense)
    {
        $type = TransactionType::fromString('pay');
        $transaction = Transaction::betweenAccounts(
            $type,
            $expense,
            $asset,
            $this->amount,
            $this->date,
            $this->description
        );
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

    /**
     * @test
     * @dataProvider assetAccountAndExpenseAccount
     * @expectedException PerFi\Domain\Transaction\Exception\NotExecutableTransactionException
     * @expectedExceptionMessage The refund transaction cannot be executed between Cash, asset and Groceries, expense accounts
     */
    public function refund_transaction_can_not_be_executed_between_asset_and_expense($asset, $expense)
    {
        $type = TransactionType::fromString('refund');
        $transaction = Transaction::betweenAccounts(
            $type,
            $asset,
            $expense,
            $this->amount,
            $this->date,
            $this->description
        );
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
