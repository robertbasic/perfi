<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Event;

use PerFi\Domain\Transaction\Transaction;

class PaymentMade
{
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * Create a payment made event
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * The transaction that is the payment
     *
     * @return Transaction
     */
    public function transaction() : Transaction
    {
        return $this->transaction;
    }
}
