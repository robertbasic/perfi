<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Event;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Event\TransactionExecuted;
use PerFi\Domain\Transaction\Transaction;

class TransactionExecutedTest extends TestCase
{
    /**
     * @test
     */
    public function transaction_is_payload()
    {
        $source = Account::byStringType('asset', 'Cash');
        $destination = Account::byStringType('expense', 'Groceries');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $source,
            $destination,
            $amount,
            $description
        );

        $event = new TransactionExecuted($transaction);

        $result = $event->payload();

        self::assertSame($transaction, $result);
    }
}
