<?php
declare(strict_types=1);

namespace PerFi\Application\Repository;

use Doctrine\DBAL\Query\QueryBuilder;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionId;
use PerFi\Domain\Transaction\TransactionRepository as TransactionRepositoryInterface;
use PerFi\Application\Factory\TransactionFactory;
use PerFi\Application\Repository\Repository;

/**
 * TransactionRepository
 */
class TransactionRepository extends Repository
    implements TransactionRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(Transaction $transaction)
    {
        if ($this->exists($transaction)) {
            return $this->update($transaction);
        }

        return $this->insert($transaction);
    }

    /**
     * {@inheritdoc}
     */
    public function get(TransactionId $transactionId) : Transaction
    {
        $query = $this->getQuery();

        $statement = $query->where('t.transaction_id = :transactionId')
            ->setParameter('transactionId', $transactionId)
            ->execute();

        return $this->mapToEntity($statement->fetch());
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
                't.date', 't.record_date', 't.description', 't.refunded',
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

    private function exists(Transaction $transaction) : bool
    {
        $qb = $this->getQueryBuilder();

        $statement = $qb->select('t.transaction_id')
            ->from('transaction', 't')
            ->where('t.transaction_id = :transactionId')
            ->setParameter('transactionId', (string) $transaction->id())
            ->execute();

        return (bool) $statement->fetch();
    }

    private function insert(Transaction $transaction)
    {
        $qb = $this->getQueryBuilder();

        $amount = $transaction->amount();

        $query = $qb->insert('transaction')
            ->values(
                [
                    'transaction_id' => '?',
                    'type' => '?',
                    'source_account' => '?',
                    'destination_account' => '?',
                    'amount' => '?',
                    'currency' => '?',
                    'date' => '?',
                    'record_date' => '?',
                    'description' => '?',
                    'refunded' => '?',
                ]
            )
            ->setParameter(0, (string) $transaction->id())
            ->setParameter(1, (string) $transaction->type())
            ->setParameter(2, (string) $transaction->sourceAccount()->id())
            ->setParameter(3, (string) $transaction->destinationAccount()->id())
            ->setParameter(4, $amount->getAmount())
            ->setParameter(5, (string) $amount->getCurrency())
            ->setParameter(6, $transaction->date()->format('Y-m-d H:i:s'))
            ->setParameter(7, $transaction->recordDate()->format('Y-m-d H:i:s'))
            ->setParameter(8, $transaction->description())
            ->setParameter(9, (int) $transaction->refunded());

        $query->execute();
    }

    private function update(Transaction $transaction)
    {
        $qb = $this->getQueryBuilder();

        $amount = $transaction->amount();

        $query = $qb->update('transaction')
            ->set('type', '?')
            ->set('source_account', '?')
            ->set('destination_account', '?')
            ->set('amount', '?')
            ->set('currency', '?')
            ->set('date', '?')
            ->set('record_date', '?')
            ->set('description', '?')
            ->set('refunded', '?')
            ->where('transaction_id = ?')
            ->setParameter(0, (string) $transaction->type())
            ->setParameter(1, (string) $transaction->sourceAccount()->id())
            ->setParameter(2, (string) $transaction->destinationAccount()->id())
            ->setParameter(3, $amount->getAmount())
            ->setParameter(4, (string) $amount->getCurrency())
            ->setParameter(5, $transaction->date()->format('Y-m-d H:i:s'))
            ->setParameter(6, $transaction->recordDate()->format('Y-m-d H:i:s'))
            ->setParameter(7, $transaction->description())
            ->setParameter(8, (int) $transaction->refunded())
            ->setParameter(9, (string) $transaction->id());

        $query->execute();
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