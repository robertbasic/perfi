<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\EventSubscriber\CreditAssetAccountWhenPaymentMade;
use PerFi\Domain\Transaction\Event\PaymentMade;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionType;

class CreditAssetAccountWhenPaymentMadeTest extends TestCase
{
    /**
     * @test
     */
    public function asset_account_is_credited()
    {
        $type = TransactionType::fromString('pay');
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');
        $asset = Account::byTypeWithTitle($asset, 'Cash');
        $expense = Account::byTypeWithTitle($expense, 'Groceries');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $date = TransactionDate::fromString('2017-03-12');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $type,
            $asset,
            $expense,
            $amount,
            $date,
            $description
        );

        $event = new PaymentMade($transaction);

        $eventSubscriber = new CreditAssetAccountWhenPaymentMade();
        $eventSubscriber->__invoke($event);

        $balances = $asset->balances();

        self::assertNotEmpty($balances);
    }
}
