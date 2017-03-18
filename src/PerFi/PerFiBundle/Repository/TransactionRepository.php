<?php

namespace PerFi\PerFiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountRepository;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionId;
use PerFi\Domain\Transaction\TransactionRecordDate;
use PerFi\Domain\Transaction\TransactionRepository as TransactionRepositoryInterface;
use PerFi\Domain\Transaction\TransactionType;
use PerFi\PerFiBundle\Entity\Transaction as DtoTransaction;

/**
 * TransactionRepository
 */
class TransactionRepository extends EntityRepository
    implements TransactionRepositoryInterface
{

    /**
     * @var AccountRepository
     */
    private $accountRepository;

    public function setAccountRepository(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

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
        $queryBuilder = $this->createQueryBuilder('t');

        $dtos = $queryBuilder
            ->select('t')
            ->getQuery()
            ->getResult();

        return $this->dtosToEntities($dtos);
    }

    private function dtosToEntities($dtos)
    {
        $transactions = [];

        foreach ($dtos as $dto) {
            $transactions[] = $this->dtoToEntity($dto);
        }

        return $transactions;
    }

    private function dtoToEntity($dto)
    {
        $sourceAccountId = AccountId::fromString($dto->getSourceAccount());
        $destinationAccountId = AccountId::fromString($dto->getDestinationAccount());

        $transaction = Transaction::withId(
            TransactionId::fromString($dto->getTransactionId()),
            TransactionType::fromString($dto->getType()),
            $this->accountRepository->get($sourceAccountId),
            $this->accountRepository->get($destinationAccountId),
            MoneyFactory::centsInCurrency($dto->getAmount(), $dto->getCurrency()),
            TransactionDate::fromString($dto->getDate()->format('Y-m-d')),
            TransactionRecordDate::fromString($dto->getRecordDate()->format('Y-m-d H:i:s')),
            $dto->getDescription()
        );

        return $transaction;
    }
}
