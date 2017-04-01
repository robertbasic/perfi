<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionId;

interface TransactionRepository
{
    /**
     * Save a transaction to the repository
     *
     * @param Transaction $transaction
     */
    public function save(Transaction $transaction);

    /**
     * Get an account from the repository
     *
     * @param TransactionId $transactionId
     * @return Transaction
     */
    public function get(TransactionId $transactionId) : Transaction;

    /**
     * Get all transactions from the repository
     *
     * @return array
     */
    public function getAll() : array;
}
