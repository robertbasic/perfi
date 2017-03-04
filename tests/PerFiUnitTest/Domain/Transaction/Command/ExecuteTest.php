<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Transaction\Command\Execute;
use PerFi\Domain\Transaction\Transaction;

class ExecuteTest extends TestCase
{
    /**
     * @test
     */
    public function transaction_is_payload()
    {
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');

        $sourceAccount = Account::byType($asset, 'Cash');
        $destinationAccount = Account::byType($expense, 'Groceries');
        $amount = '500';
        $currency = 'RSD';
        $description = 'supermarket';

        $command = new Execute(
            $sourceAccount,
            $destinationAccount,
            $amount,
            $currency,
            $description
        );

        $payload = $command->payload();

        self::assertInstanceOf(Transaction::class, $payload);
    }
}
