<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Event;

use PerFi\Domain\Event;
use PerFi\Domain\Transaction\Transaction;

class TransactionExecuted implements Event
{

    /**
     * @var Transaction
     *
     */
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function payload() : Transaction
    {
        return $this->transaction;
    }
}
