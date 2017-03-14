<?php

namespace PerFi\PerFiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionRepository as TransactionRepositoryInterface;
use PerFi\PerFiBundle\Entity\Transaction as DtoTransaction;

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
        $entity = new DtoTransaction();
        $entity->setTransactionId((string) $transaction->id());
        $entity->setType((string) $transaction->type());
        $entity->setSourceAccount((string) $transaction->sourceAccount()->id());
        $entity->setDestinationAccount((string) $transaction->destinationAccount()->id());
        $entity->setDate($transaction->date());
        $entity->setDescription($transaction->description());

        $amount = $transaction->amount();
        $entity->setAmount($amount->getAmount());
        $entity->setCurrency((string) $amount->getCurrency());

        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getAll() : array
    {
        return [];
    }
}
