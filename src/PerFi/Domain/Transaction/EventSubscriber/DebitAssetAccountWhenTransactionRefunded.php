<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\EventSubscriber;

use PerFi\Domain\Account\Event\DestinationAccountDebited;
use PerFi\Domain\Transaction\Event\TransactionRefunded;
use SimpleBus\Message\Bus\MessageBus;

class DebitAssetAccountWhenTransactionRefunded
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

        $destinationAccount = $transaction->destinationAccount();

        $event = new DestinationAccountDebited($destinationAccount);

        $this->eventBus->handle($event);
    }
}
