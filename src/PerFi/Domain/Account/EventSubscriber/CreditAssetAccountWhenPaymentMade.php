<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\EventSubscriber;

use PerFi\Domain\Transaction\Event\PaymentMade;

class CreditAssetAccountWhenPaymentMade extends CreditSourceAccount
{
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

        $this->creditSourceAccount($transaction);
    }
}
