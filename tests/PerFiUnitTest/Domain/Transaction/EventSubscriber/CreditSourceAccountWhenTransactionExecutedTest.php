<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\EventSubscriber\CreditSourceAccountWhenTransactionExecuted;
use PerFi\Domain\Transaction\Event\TransactionExecuted;
use PerFi\Domain\Transaction\Transaction;

class CreditSourceAccountWhenTransactionExecutedTest extends TestCase
{
    /**
     * @test
     */
    public function source_account_is_credited()
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

        $eventSubscriber = new CreditSourceAccountWhenTransactionExecuted();
        $eventSubscriber->__invoke($event);

        $balances = $source->balances();

        self::assertNotEmpty($balances);
    }
}
