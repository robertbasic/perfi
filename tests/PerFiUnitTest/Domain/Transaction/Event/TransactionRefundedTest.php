<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Event;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Event\TransactionRefunded;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionType;

class TransactionRefundedTest extends TestCase
{
    /**
     * @test
     */
    public function transaction_is_set_on_event()
    {
        $type = TransactionType::fromString('refund');
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');
        $source = Account::byTypeWithTitle($expense, 'Groceries');
        $destination = Account::byTypeWithTitle($asset, 'Cash');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $date = TransactionDate::fromString('2017-03-12');
        $description = 'Refund groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $type,
            $source,
            $destination,
            $amount,
            $date,
            $description
        );

        $event = new TransactionRefunded($transaction);

        $result = $event->transaction();

        self::assertSame($transaction, $result);
    }
}
