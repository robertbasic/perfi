<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\EventSubscriber;

use PerFi\Domain\Transaction\Event\TransactionExecuted;

class DebitDestinationAccountWhenTransactionExecuted
{
    /**
     * Handle the transaction executed event
     *
     * Debit the destination account of the transaction.
     *
     * @param TransactionExecuted $transactionExecuted
     */
    public function __invoke(TransactionExecuted $event)
    {
        $transaction = $event->transaction();

        $transaction->debitDestinationAccount();
    }
}
