<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\Command\Execute;
use PerFi\Domain\Transaction\Transaction;

class ExecuteTest extends TestCase
{
    /**
     * @test
     */
    public function transaction_is_payload()
    {
        $asset = 'asset';
        $expense = 'expense';
        $sourceAccount = Account::byStringType($asset, 'Cash');
        $destinationAccount = Account::byStringType($expense, 'Groceries');
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
