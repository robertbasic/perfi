<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

use PerFi\Domain\Transaction\Transaction;

interface TransactionRepository
{
    public function add(Transaction $transaction);

    public function getAll() : array;
}
