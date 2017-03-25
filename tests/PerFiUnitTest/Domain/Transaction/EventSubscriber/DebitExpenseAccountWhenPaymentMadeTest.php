<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\EventSubscriber\DebitExpenseAccountWhenPaymentMade;
use PerFi\Domain\Transaction\Event\PaymentMade;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionType;

class DebitExpenseAccountWhenPaymentMadeTest extends TestCase
{
    /**
     * @test
     */
    public function expense_account_is_debited()
    {
        $type = TransactionType::fromString('pay');
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');
        $source = Account::byTypeWithTitle($asset, 'Cash');
        $destination = Account::byTypeWithTitle($expense, 'Groceries');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $date = TransactionDate::fromString('2017-03-12');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $type,
            $source,
            $destination,
            $amount,
            $date,
            $description
        );

        $event = new PaymentMade($transaction);

        $eventSubscriber = new DebitExpenseAccountWhenPaymentMade();
        $eventSubscriber->__invoke($event);

        $balances = $destination->balances();

        self::assertNotEmpty($balances);
    }
}
