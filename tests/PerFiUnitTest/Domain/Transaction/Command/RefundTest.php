<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Command\Refund;
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
        $amount = '500';
        $currency = 'RSD';
        $description = 'supermarket';

        $command = new Refund(
            $sourceAccount,
            $destinationAccount,
            $amount,
            $currency,
            $description
        );

        $transactionType = $command->transactionType();
        $sourceAccount = $command->sourceAccount();
        $destinationAccount = $command->destinationAccount();
        $amount = $command->amount();
        $description = $command->description();

        $expectedAmount = MoneyFactory::amountInCurrency('500', 'RSD');

        self::assertInstanceOf(TransactionType::class, $transactionType);
        self::assertSame('refund', (string) $transactionType);

        self::assertInstanceOf(Account::class, $sourceAccount);
        self::assertInstanceOf(Account::class, $destinationAccount);

        self::assertTrue($expectedAmount->equals($amount));

        self::assertSame('supermarket', $description);
    }
}
