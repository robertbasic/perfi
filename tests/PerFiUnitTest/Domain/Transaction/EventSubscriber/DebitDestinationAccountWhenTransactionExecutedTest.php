<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\EventSubscriber\DebitDestinationAccountWhenTransactionExecuted;
use PerFi\Domain\Transaction\Event\TransactionExecuted;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class DebitDestinationAccountWhenTransactionExecutedTest extends TestCase
{
    /**
     * @test
     */
    public function destination_account_is_debited()
    {
        $type = TransactionType::fromString('pay');
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');
        $source = Account::byTypeWithTitle($asset, 'Cash');
        $destination = Account::byTypeWithTitle($expense, 'Groceries');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $type,
            $source,
            $destination,
            $amount,
            $description
        );

        $event = new TransactionExecuted($transaction);

        $eventSubscriber = new DebitDestinationAccountWhenTransactionExecuted();
        $eventSubscriber->__invoke($event);

        $balances = $destination->balances();

        self::assertNotEmpty($balances);
    }
}
