<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\EventSubscriber;

use PerFi\Domain\Event;
use PerFi\Domain\EventSubscriber;

class DebitDestinationAccountWhenTransactionExecuted implements EventSubscriber
{
    /**
     * Handle the transaction executed event
     *
     * Debit the destination account of the transaction.
     *
     * @param Event $transactionExecuted
     */
    public function __invoke(Event $transactionExecuted)
    {
        $transaction = $transactionExecuted->payload();

        $transaction->debitDestinationAccount();
    }
}
