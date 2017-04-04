<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\EventSubscriber;

use PerFi\Domain\Account\Event\DestinationAccountDebited;
use PerFi\Domain\Transaction\Transaction;
use SimpleBus\Message\Bus\MessageBus;

abstract class DebitDestinationAccount
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
     * Debit the destination account of the transaction
     *
     * @param Transaction $transaction
     */
    protected function debitDestinationAccount(Transaction $transaction)
    {
        $transaction->debitDestinationAccount();

        $destinationAccount = $transaction->destinationAccount();

        $event = new DestinationAccountDebited($destinationAccount);

        $this->eventBus->handle($event);
    }
}
