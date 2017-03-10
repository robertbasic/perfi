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

    public function setup()
    {
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');

        $this->type = TransactionType::fromString('pay');
        $this->source = Account::byTypeWithTitle($asset, 'Cash');
        $this->destination = Account::byTypeWithTitle($expense, 'Groceries');
    }

    /**
     * @test
     */
    public function transaction_between_two_accounts_can_be_created()
    {
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $this->type,
            $this->source,
            $this->destination,
            $amount,
            $description
        );

        self::assertInstanceOf(TransactionId::class, $transaction->id());
        self::assertInstanceOf(TransactionDate::class, $transaction->date());
        self::assertSame($description, $transaction->description());

        self::assertInstanceOf(TransactionType::class, $transaction->type());

        self::assertInstanceOf(Account::class, $transaction->sourceAccount());
        self::assertSame($this->source->id(), $transaction->sourceAccount()->id());

        self::assertInstanceOf(Account::class, $transaction->destinationAccount());
        self::assertSame($this->destination->id(), $transaction->destinationAccount()->id());

        self::assertInstanceOf(Money::class, $transaction->amount());
        self::assertSame($amount->getAmount(), $transaction->amount()->getAmount());
        self::assertSame($amount->getCurrency(), $transaction->amount()->getCurrency());
    }

    /**
     * @test
     */
    public function credits_source_account()
    {
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $this->type,
            $this->source,
            $this->destination,
            $amount,
            $description
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
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $this->type,
            $this->source,
            $this->destination,
            $amount,
            $description
        );

        $transaction->debitDestinationAccount();

        $balances = $this->destination->balances();

        self::assertNotEmpty($balances);
    }
}
