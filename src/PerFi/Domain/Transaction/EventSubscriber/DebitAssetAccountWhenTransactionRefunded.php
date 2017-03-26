<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\EventSubscriber;

use PerFi\Domain\Transaction\Event\TransactionRefunded;

class DebitAssetAccountWhenTransactionRefunded
{
    /**
     * Handle the transaction refunded event
     *
     * Debit the asset/destination account of the transaction.
     *
     * @param TransactionRefunded $event
     */
    public function __invoke(TransactionRefunded $event)
    {
        $transaction = $event->refundTransaction();

        $transaction->debitDestinationAccount();
    }
}
