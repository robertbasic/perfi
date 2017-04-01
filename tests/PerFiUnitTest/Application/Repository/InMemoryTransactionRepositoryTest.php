<?php
declare(strict_types=1);

namespace PerFiUnitTest\Application\Repository;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Application\Repository\InMemoryTransactionRepository;

class InMemoryTransactionRepositoryTest extends TestCase
{
    use TransactionTrait;

    /**
     * @test
     */
    public function can_add_transaction_to_repository()
    {
        $transaction = $this->payTransaction();

        $repository = new InMemoryTransactionRepository();

        $repository->add($transaction);

        $transactions = $repository->getAll();

        foreach ($transactions as $id => $result) {
            self::assertSame((string) $transaction->id(), $id);
            self::assertSame($result, $transaction);

            $result = $repository->get($transaction->id());

            self::assertSame($result, $transaction);
        }
    }
}
