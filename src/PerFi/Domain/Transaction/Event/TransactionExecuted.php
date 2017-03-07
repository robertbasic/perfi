<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Event;

use PerFi\Domain\Event;
use PerFi\Domain\Transaction\Transaction;

class TransactionExecuted implements Event
{
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * Create a transaction executed event
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * The payload of the transaction executed event
     *
     * @return Transaction
     */
    public function payload() : Transaction
    {
        return $this->transaction;
    }
}
