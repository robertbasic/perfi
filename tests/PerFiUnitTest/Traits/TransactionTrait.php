<?php
declare(strict_types=1);

namespace PerFiUnitTest\Traits;

use Mockery as m;
use PerFiUnitTest\Traits\AccountTrait;
use PerFiUnitTest\Traits\AmountTrait;
use PerFiUnitTest\Traits\TransactionTypeTrait;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionRepository;

trait TransactionTrait
{
    use TransactionTypeTrait;
    use AccountTrait;
    use AmountTrait;

    public function payTransaction() : Transaction
    {
        return Transaction::betweenAccounts(
            $this->pay(),
            $this->assetAccount(),
            $this->expenseAccount(),
            $this->amount('500', 'RSD'),
            TransactionDate::fromString('2017-03-12'),
            'transaction description'
        );
    }

    public function refundTransaction() : Transaction
    {
        return Transaction::betweenAccounts(
            $this->refund(),
            $this->assetAccount(),
            $this->expenseAccount(),
            $this->amount('500', 'RSD'),
            TransactionDate::fromString('2017-03-12'),
            'transaction description'
        );
    }

    public function mockTransaction() : Transaction
    {
        return m::mock(Transaction::class);
    }

    public function mockTransactionRepository(Transaction ...$transactions) : TransactionRepository
    {
        $transactionRepository = m::mock(TransactionRepository::class);
        $transactionRepository->shouldReceive('get')
            ->times(count($transactions))
            ->andReturn(...$transactions);

        return $transactionRepository;
    }
}
