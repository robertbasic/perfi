<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\EventSubscriber;

use PerFi\Domain\Transaction\Event\PaymentMade;

class DebitExpenseAccountWhenPaymentMade
{
    /**
     * Handle the payment made event
     *
     * Debit the expense/destination account of the transaction.
     *
     * @param PaymentMade $event
     */
    public function __invoke(PaymentMade $event)
    {
        $transaction = $event->transaction();

        $transaction->debitDestinationAccount();
    }
}
