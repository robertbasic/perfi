<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\EventSubscriber;

use PerFi\Domain\Account\Event\SourceAccountCredited;
use PerFi\Domain\Transaction\Event\TransactionRefunded;
use SimpleBus\Message\Bus\MessageBus;

class CreditExpenseAccountWhenTransactionRefunded
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
     * Credit the expense/source account of the transaction.
     *
     * @param TransactionRefunded $event
     */
    public function __invoke(TransactionRefunded $event)
    {
        $transaction = $event->refundTransaction();

        $transaction->creditSourceAccount();

        $sourceAccount = $transaction->sourceAccount();

        $event = new SourceAccountCredited($sourceAccount);

        $this->eventBus->handle($event);
    }
}
