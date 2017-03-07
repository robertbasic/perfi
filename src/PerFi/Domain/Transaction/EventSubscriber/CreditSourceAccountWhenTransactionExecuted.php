<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\EventSubscriber;

use PerFi\Domain\Event;
use PerFi\Domain\EventSubscriber;

class CreditSourceAccountWhenTransactionExecuted implements EventSubscriber
{
    public function __invoke(Event $transactionExecuted)
    {
        $transaction = $transactionExecuted->payload();

        $transaction->creditSourceAccount();
    }
}
