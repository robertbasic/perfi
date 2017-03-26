<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\EventSubscriber;

use PerFi\Domain\Transaction\Event\TransactionRefunded;

class CreditExpenseAccountWhenTransactionRefunded
{
    /**
     * Handle the transaction refunded event
     *
     * Credit the expense/source account of the transaction.
     *
     * @param TransactionRefunded $event
     */
    public function __invoke(TransactionRefunded $event)
    {
        $transaction = $event->refundTransaction();

        $transaction->creditSourceAccount();
    }
}
