<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Command\Refund;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionType;

class RefundTest extends TestCase
{
    /**
     * @test
     */
    public function refund_transaction_command_is_created()
    {
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');
        $sourceAccount = Account::byTypeWithTitle($asset, 'Cash');
        $destinationAccount = Account::byTypeWithTitle($expense, 'Groceries');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $date = TransactionDate::fromString('2017-03-12');
        $description = 'supermarket';

        $transaction = Transaction::betweenAccounts(
            TransactionType::fromString('pay'),
            $sourceAccount,
            $destinationAccount,
            $amount,
            $date,
            $description
        );

        $command = new Refund($transaction);

        $transactionType = $command->transactionType();
        $sourceAccount = $command->sourceAccount();
        $destinationAccount = $command->destinationAccount();
        $amount = $command->amount();
        $date = $command->date();
        $description = $command->description();

        $expectedAmount = MoneyFactory::amountInCurrency('500', 'RSD');

        self::assertInstanceOf(TransactionType::class, $transactionType);
        self::assertSame('refund', (string) $transactionType);

        self::assertInstanceOf(Account::class, $sourceAccount);
        self::assertInstanceOf(Account::class, $destinationAccount);

        self::assertSame('expense', (string) $sourceAccount->type());
        self::assertSame('asset', (string) $destinationAccount->type());

        self::assertTrue($expectedAmount->equals($amount));

        self::assertInstanceOf(TransactionDate::class, $date);

        self::assertSame('Refund supermarket', $description);
    }

    /**
     * @test
     * @expectedException PerFi\Domain\Transaction\Exception\NotRefundableTransactionException
     * @expectedExceptionMessageRegExp ~The transaction cannot be refunded, transaction ID: .*~
     */
    public function refund_command_for_refunding_a_refund_transaction_can_not_be_created()
    {
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');
        $sourceAccount = Account::byTypeWithTitle($asset, 'Cash');
        $destinationAccount = Account::byTypeWithTitle($expense, 'Groceries');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $date = TransactionDate::fromString('2017-03-12');
        $description = 'supermarket';

        $transaction = Transaction::betweenAccounts(
            TransactionType::fromString('refund'),
            $destinationAccount,
            $sourceAccount,
            $amount,
            $date,
            $description
        );

        $command = new Refund($transaction);
    }
}
