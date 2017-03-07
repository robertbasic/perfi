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

    /**
     * {@inheritdoc}
     */
    public function add(Transaction $transaction)
    {
        $this->transactions[(string) $transaction->id()] = $transaction;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        return $this->transactions;
    }
}
