<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\EventSubscriber;

use PerFi\Domain\Transaction\Event\TransactionRefunded;

class CreditExpenseAccountWhenTransactionRefunded extends CreditSourceAccount
{
    /*
     * Handle the transaction refunded event
     *
     * Credit the expense/source account of the transaction.
     *
     * @param TransactionRefunded $event
     */
    public function __invoke(TransactionRefunded $event)
    {
        $transaction = $event->refundTransaction();

        $this->creditSourceAccount($transaction);
    }
}
