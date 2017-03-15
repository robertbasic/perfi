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
    private $source;

    /**
     * @var Account
     */
    private $destination;

    /**
     * @var Money
     */
    private $amount;

    /**
     * @var string
     */
    private $description;

    public function setup()
    {
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');

        $this->type = TransactionType::fromString('pay');
        $this->source = Account::byTypeWithTitle($asset, 'Cash');
        $this->destination = Account::byTypeWithTitle($expense, 'Groceries');
        $this->amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $this->description = 'groceries for dinner';
    }

    /**
     * @test
     */
    public function transaction_between_two_accounts_can_be_created()
    {
        $transaction = Transaction::betweenAccounts(
            $this->type,
            $this->source,
            $this->destination,
            $this->amount,
            $this->description
        );

        self::assertInstanceOf(TransactionId::class, $transaction->id());
        self::assertInstanceOf(TransactionDate::class, $transaction->date());
        self::assertSame($this->description, $transaction->description());

        self::assertInstanceOf(TransactionType::class, $transaction->type());

        self::assertInstanceOf(Account::class, $transaction->sourceAccount());
        self::assertSame($this->source->id(), $transaction->sourceAccount()->id());

        self::assertInstanceOf(Account::class, $transaction->destinationAccount());
        self::assertSame($this->destination->id(), $transaction->destinationAccount()->id());

        self::assertInstanceOf(Money::class, $transaction->amount());
        self::assertSame($this->amount->getAmount(), $transaction->amount()->getAmount());
        self::assertSame($this->amount->getCurrency(), $transaction->amount()->getCurrency());
    }

    /**
     * @test
     */
    public function credits_source_account()
    {
        $transaction = Transaction::betweenAccounts(
            $this->type,
            $this->source,
            $this->destination,
            $this->amount,
            $this->description
        );

        $transaction->creditSourceAccount();

        $balances = $this->source->balances();

        self::assertNotEmpty($balances);
    }

    /**
     * @test
     */
    public function debits_destination_account()
    {
        $transaction = Transaction::betweenAccounts(
            $this->type,
            $this->source,
            $this->destination,
            $this->amount,
            $this->description
        );

        $transaction->debitDestinationAccount();

        $balances = $this->destination->balances();

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
            $this->description
        );

        self::assertInstanceOf(Transaction::class, $transaction);
    }

    /**
     * @test
     * @dataProvider assetAccountAndExpenseAccount
     * @expectedException RuntimeException
     */
    public function pay_transaction_can_not_be_executed_between_expense_and_asset($asset, $expense)
    {
        $type = TransactionType::fromString('pay');
        $transaction = Transaction::betweenAccounts(
            $type,
            $expense,
            $asset,
            $this->amount,
            $this->description
        );
    }

    /**
     * @test
     * @dataProvider assetAccountAndExpenseAccount
     */
    public function charge_transaction_can_be_executed_between_expense_and_asset($asset, $expense)
    {
        $type = TransactionType::fromString('charge');
        $transaction = Transaction::betweenAccounts(
            $type,
            $expense,
            $asset,
            $this->amount,
            $this->description
        );

        self::assertInstanceOf(Transaction::class, $transaction);
    }

    /**
     * @test
     * @dataProvider assetAccountAndExpenseAccount
     * @expectedException RuntimeException
     */
    public function charge_transaction_can_not_be_executed_between_asset_and_expense($asset, $expense)
    {
        $type = TransactionType::fromString('charge');
        $transaction = Transaction::betweenAccounts(
            $type,
            $asset,
            $expense,
            $this->amount,
            $this->description
        );
    }

    /**
     * @test
     * @dataProvider assetAccountAndExpenseAccount
     */
    public function pay_back_transaction_can_be_executed_between_expense_and_asset($asset, $expense)
    {
        $type = TransactionType::fromString('payback');
        $transaction = Transaction::betweenAccounts(
            $type,
            $expense,
            $asset,
            $this->amount,
            $this->description
        );

        self::assertInstanceOf(Transaction::class, $transaction);
    }

    /**
     * @test
     * @dataProvider assetAccountAndExpenseAccount
     * @expectedException RuntimeException
     */
    public function pay_back_transaction_can_not_be_executed_between_asset_and_expense($asset, $expense)
    {
        $type = TransactionType::fromString('payback');
        $transaction = Transaction::betweenAccounts(
            $type,
            $asset,
            $expense,
            $this->amount,
            $this->description
        );
    }

    /**
     * @test
     * @dataProvider assetAccountAndExpenseAccount
     */
    public function refund_transaction_can_be_executed_between_asset_and_expense($asset, $expense)
    {
        $type = TransactionType::fromString('refund');
        $transaction = Transaction::betweenAccounts(
            $type,
            $asset,
            $expense,
            $this->amount,
            $this->description
        );

        self::assertInstanceOf(Transaction::class, $transaction);
    }

    /**
     * @test
     * @dataProvider assetAccountAndExpenseAccount
     * @expectedException RuntimeException
     */
    public function refund_transaction_can_not_be_executed_between_expense_and_asset($asset, $expense)
    {
        $type = TransactionType::fromString('refund');
        $transaction = Transaction::betweenAccounts(
            $type,
            $expense,
            $asset,
            $this->amount,
            $this->description
        );
    }

    public function assetAccountAndExpenseAccount()
    {
        $assetType = AccountType::fromString('asset');
        $asset = Account::byTypeWithTitle($assetType, 'Cash');

        $expenseType = AccountType::fromString('expense');
        $expense = Account::byTypeWithTitle($expenseType, 'Cash');

        return [
            [
                $asset,
                $expense,
            ],
        ];
    }
}
