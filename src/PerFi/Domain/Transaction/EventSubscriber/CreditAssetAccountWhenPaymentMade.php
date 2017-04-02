<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\EventSubscriber;

use PerFi\Domain\Account\Event\SourceAccountCredited;
use PerFi\Domain\Transaction\Event\PaymentMade;
use SimpleBus\Message\Bus\MessageBus;

class CreditAssetAccountWhenPaymentMade
{
    /**
     * @var MessageBus
     */
    private $eventBus;

    /**
     * Create the event subscribe for when the payment was made
     *
     * @param MessageBus $eventBus
     */
    public function __construct(MessageBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    /**
     * Handle the payment made event
     *
     * Credit the asset/source account of the transaction.
     *
     * @param PaymentMade $event
     */
    public function __invoke(PaymentMade $event)
    {
        $transaction = $event->transaction();

        $transaction->creditSourceAccount();

        $sourceAccount = $transaction->sourceAccount();

        $event = new SourceAccountCredited($sourceAccount);

        $this->eventBus->handle($event);
    }
}
