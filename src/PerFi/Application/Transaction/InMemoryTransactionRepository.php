<?php
declare(strict_types=1);

namespace PerFi\Application\Transaction;

use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionRepository;

class InMemoryTransactionRepository implements TransactionRepository
{

    /**
     * @var Transaction[]
     */
    private $transactions;

    public function add(Transaction $transaction)
    {
        $this->transactions[] = $transaction;
    }

    public function getAll() : array
    {
        return $this->transactions;
    }
}
