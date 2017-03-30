<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Transaction\EventSubscriber\CreditExpenseAccountWhenTransactionRefunded;
use PerFi\Domain\Transaction\Event\TransactionRefunded;

class CreditExpenseAccountWhenTransactionRefundedTest extends TestCase
{
    use TransactionTrait;

    /**
     * @test
     */
    public function expense_account_is_credited()
    {
        $refundedTransaction = $this->payTransaction();

        $transaction = $this->refundTransaction();

        $expense = $transaction->sourceAccount();

        $event = new TransactionRefunded($transaction, $refundedTransaction);

        $eventSubscriber = new CreditExpenseAccountWhenTransactionRefunded();
        $eventSubscriber->__invoke($event);

        $balances = $expense->balances();

        self::assertNotEmpty($balances);
    }
}
