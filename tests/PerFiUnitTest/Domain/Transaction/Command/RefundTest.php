<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use Money\Money;
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
     * @var Account
     */
    private $assetAccount;

    /**
     * @var Account
     */
    private $expenseAccount;

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
        $this->assetAccount = Account::byTypeWithTitle($asset, 'Cash');
        $this->expenseAccount = Account::byTypeWithTitle($expense, 'Groceries');
        $this->amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $this->date = TransactionDate::fromString('2017-03-12');
        $this->description = 'supermarket';
    }

    /**
     * @test
     */
    public function refund_transaction_command_is_created()
    {
        $transaction = Transaction::betweenAccounts(
            TransactionType::fromString('pay'),
            $this->assetAccount,
            $this->expenseAccount,
            $this->amount,
            $this->date,
            $this->description
        );

        $command = new Refund($transaction);

        $transactionType = $command->transactionType();
        $sourceAccount = $command->sourceAccount();
        $destinationAccount = $command->destinationAccount();
        $amount = $command->amount();
        $date = $command->date();
        $description = $command->description();

        $expectedAmount = MoneyFactory::amountInCurrency('500', 'RSD');

        self::assertSame($transaction, $command->transaction());

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
}
