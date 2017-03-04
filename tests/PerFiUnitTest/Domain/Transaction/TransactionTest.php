<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction;

use Money\Money;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionId;

class TransactionTest extends TestCase
{
    /**
     * @test
     */
    public function transaction_between_two_accounts_can_be_created()
    {
        $asset = 'asset';
        $expense = 'expense';
        $source = Account::byStringType($asset, 'Cash');
        $destination = Account::byStringType($expense, 'Groceries');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $source,
            $destination,
            $amount,
            $description
        );

        self::assertInstanceOf(TransactionId::class, $transaction->id());
        self::assertInstanceOf(TransactionDate::class, $transaction->date());
        self::assertSame($description, $transaction->description());

        self::assertInstanceOf(Account::class, $transaction->sourceAccount());
        self::assertSame($source->id(), $transaction->sourceAccount()->id());

        self::assertInstanceOf(Account::class, $transaction->destinationAccount());
        self::assertSame($destination->id(), $transaction->destinationAccount()->id());

        self::assertInstanceOf(Money::class, $transaction->amount());
        self::assertSame($amount->getAmount(), $transaction->amount()->getAmount());
        self::assertSame($amount->getCurrency(), $transaction->amount()->getCurrency());
    }
}
