<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\EventSubscriber;

use PerFi\Domain\Transaction\Event\TransactionExecuted;

class CreditSourceAccountWhenTransactionExecuted
{
    /**
     * Handle the transaction executed event
     *
     * Credit the source account of the transaction.
     *
     * @param TransactionExecuted $transactionExecuted
     */
    public function __invoke(TransactionExecuted $event)
    {
        $transaction = $event->transaction();

        $transaction->creditSourceAccount();
    }
}
