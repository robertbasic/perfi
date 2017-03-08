<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Command\ExecuteTransaction;

class ExecuteTransactionTest extends TestCase
{
    /**
     * @test
     */
    public function transaction_is_payload()
    {
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');
        $sourceAccount = Account::byTypeWithTitle($asset, 'Cash');
        $destinationAccount = Account::byTypeWithTitle($expense, 'Groceries');
        $amount = '500';
        $currency = 'RSD';
        $description = 'supermarket';

        $command = new ExecuteTransaction(
            $sourceAccount,
            $destinationAccount,
            $amount,
            $currency,
            $description
        );

        $sourceAccount = $command->sourceAccount();
        $destinationAccount = $command->destinationAccount();
        $amount = $command->amount();
        $description = $command->description();

        $expectedAmount = MoneyFactory::amountInCurrency('500', 'RSD');

        self::assertInstanceOf(Account::class, $sourceAccount);
        self::assertInstanceOf(Account::class, $destinationAccount);
        self::assertTrue($expectedAmount->equals($amount));
        self::assertSame('supermarket', $description);
    }
}
