<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Command\ExecuteTransaction;
use PerFi\Domain\Transaction\Transaction;

class ExecuteTransactionTest extends TestCase
{
    /**
     * @test
     */
    public function transaction_is_payload()
    {
        $sourceAccount = Account::byStringType('asset', 'Cash');
        $destinationAccount = Account::byStringType('expense', 'Groceries');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $description = 'supermarket';

        $command = new ExecuteTransaction(
            $sourceAccount,
            $destinationAccount,
            $amount,
            $description
        );

        $payload = $command->payload();

        self::assertInstanceOf(Transaction::class, $payload);
    }
}
