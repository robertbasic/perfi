<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\EventSubscriber;

use PerFi\Domain\Account\Event\SourceAccountCredited;
use PerFi\Domain\Transaction\Transaction;
use SimpleBus\Message\Bus\MessageBus;

abstract class CreditSourceAccount
{
    /**
     * @var MessageBus
     */
    private $eventBus;

    /**
     * Create the event subscribe for when the transaction was refunded
     *
     * @param MessageBus $eventBus
     */
    public function __construct(MessageBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * Credit the source account of the transaction
     *
     * @param Transaction $transaction
     */
    protected function creditSourceAccount(Transaction $transaction)
    {
        $transaction->creditSourceAccount();

        $sourceAccount = $transaction->sourceAccount();

        $event = new SourceAccountCredited($sourceAccount);

        $this->eventBus->handle($event);
    }
}
