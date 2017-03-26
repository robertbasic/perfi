<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\EventSubscriber\DebitAssetAccountWhenTransactionRefunded;
use PerFi\Domain\Transaction\Event\TransactionRefunded;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionType;

class DebitAssetAccountWhenTransactionRefundedTest extends TestCase
{
    /**
     * @test
     */
    public function asset_account_is_debited()
    {
        $type = TransactionType::fromString('refund');
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');
        $expense = Account::byTypeWithTitle($expense, 'Groceries');
        $asset = Account::byTypeWithTitle($asset, 'Cash');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $date = TransactionDate::fromString('2017-03-12');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $type,
            $expense,
            $asset,
            $amount,
            $date,
            $description
        );

        $event = new TransactionRefunded($transaction);

        $eventSubscriber = new DebitAssetAccountWhenTransactionRefunded();
        $eventSubscriber->__invoke($event);

        $balances = $asset->balances();

        self::assertNotEmpty($balances);
    }
}
