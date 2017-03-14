<?php

namespace PerFi\PerFiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionRepository as TransactionRepositoryInterface;

/**
 * TransactionRepository
 */
class TransactionRepository extends EntityRepository
    implements TransactionRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function add(Transaction $transaction)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        return [];
    }
}
