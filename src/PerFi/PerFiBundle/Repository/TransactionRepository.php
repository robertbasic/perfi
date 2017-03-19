<?php

namespace PerFi\PerFiBundle\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionRepository as TransactionRepositoryInterface;
use PerFi\PerFiBundle\Entity\Transaction as DtoTransaction;
use PerFi\PerFiBundle\Factory\TransactionFactory;
use PerFi\PerFiBundle\Repository\Repository;

/**
 * TransactionRepository
 */
class TransactionRepository extends Repository
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
        $entity->setRecordDate($transaction->recordDate());
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
        $query = $this->getQuery();

        $statement = $query->execute();

        return $this->mapToEntities($statement);
    }

    private function getQuery() : QueryBuilder
    {
        $qb = $this->getQueryBuilder();

        $query = $qb->select(
                't.transaction_id', 't.type', 't.amount', 't.currency',
                't.date', 't.record_date', 't.description',
                'sa.account_id AS source_account_id',
                'sa.title AS source_account_title',
                'sa.type AS source_account_type',
                'da.account_id AS destination_account_id',
                'da.title AS destination_account_title',
                'da.type AS destination_account_type'
            )
            ->from('transaction', 't')
            ->innerJoin('t', 'account', 'sa', 't.source_account = sa.account_id')
            ->innerJoin('t', 'account', 'da', 't.destination_account = da.account_id');

        return $query;
    }

    private function mapToEntities($statement) : array
    {
        $transactions = [];

        while ($row = $statement->fetch()) {
            $transactions[] = $this->mapToEntity($row);
        }

        return $transactions;
    }

    private function mapToEntity(array $row) : Transaction
    {
        return TransactionFactory::fromArray($row);
    }
}
