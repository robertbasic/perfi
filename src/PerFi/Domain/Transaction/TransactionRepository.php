<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

use PerFi\Domain\Transaction\Transaction;

interface TransactionRepository
{
    /**
     * Add a transaction to the repository
     *
     * @param Transaction $transaction
     */
    public function add(Transaction $transaction);

    /**
     * Get all transactions from the repository
     *
     * @return array
     */
    public function getAll() : array;
}
