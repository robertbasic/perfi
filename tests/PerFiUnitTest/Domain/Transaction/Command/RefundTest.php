<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use Money\Money;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\AccountTrait;
use PerFiUnitTest\Traits\AmountTrait;
use PerFiUnitTest\Traits\TransactionTypeTrait;
use PerFi\Domain\Account\Account;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Command\Refund;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionType;

class RefundTest extends TestCase
{
    use AccountTrait;
    use AmountTrait;
    use TransactionTypeTrait;

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

    /**
     * @var Transaction
     */
    private $transaction;

    public function setup()
    {
        $this->assetAccount = $this->assetAccount();
        $this->expenseAccount = $this->expenseAccount();
        $this->amount = $this->amount('500', 'RSD');
        $this->date = TransactionDate::fromString('2017-03-12');
        $this->description = 'supermarket';

        $this->transaction = Transaction::betweenAccounts(
            $this->pay(),
            $this->assetAccount,
            $this->expenseAccount,
            $this->amount,
            $this->date,
            $this->description
        );
    }

    /**
     * @test
     */
    public function refund_transaction_command_is_created()
    {
        $command = new Refund($this->transaction);

        $transactionType = $command->transactionType();
        $sourceAccount = $command->sourceAccount();
        $destinationAccount = $command->destinationAccount();
        $amount = $command->amount();
        $date = $command->date();
        $description = $command->description();

        $expectedAmount = MoneyFactory::amountInCurrency('500', 'RSD');

        self::assertSame($this->transaction, $command->transaction());

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
