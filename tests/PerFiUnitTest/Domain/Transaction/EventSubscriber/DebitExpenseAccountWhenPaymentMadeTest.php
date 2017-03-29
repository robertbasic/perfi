<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\EventSubscriber;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Transaction\EventSubscriber\DebitExpenseAccountWhenPaymentMade;
use PerFi\Domain\Transaction\Event\PaymentMade;

class DebitExpenseAccountWhenPaymentMadeTest extends TestCase
{
    use TransactionTrait;

    /**
     * @test
     */
    public function expense_account_is_debited()
    {
        $transaction = $this->payTransaction();

        $expense = $transaction->destinationAccount();

        $event = new PaymentMade($transaction);

        $eventSubscriber = new DebitExpenseAccountWhenPaymentMade();
        $eventSubscriber->__invoke($event);

        $balances = $expense->balances();

        self::assertNotEmpty($balances);
    }
}
