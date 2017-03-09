<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Event;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Event\TransactionExecuted;
use PerFi\Domain\Transaction\Transaction;

class TransactionExecutedTest extends TestCase
{
    /**
     * @test
     */
    public function transaction_is_set_on_event()
    {
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');
        $source = Account::byTypeWithTitle($asset, 'Cash');
        $destination = Account::byTypeWithTitle($expense, 'Groceries');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $source,
            $destination,
            $amount,
            $description
        );

        $event = new TransactionExecuted($transaction);

        $result = $event->transaction();

        self::assertSame($transaction, $result);
    }
}
