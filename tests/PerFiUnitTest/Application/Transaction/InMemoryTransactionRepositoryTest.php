<?php
declare(strict_types=1);

namespace PerFiUnitTest\Application\Transaction;

use PHPUnit\Framework\TestCase;
use PerFi\Application\Transaction\InMemoryTransactionRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Transaction;

class InMemoryTransactionRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function can_add_transaction_to_repository()
    {
        $source = Account::byStringType('asset', 'Cash');
        $destination = Account::byStringType('expense', 'Groceries');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $source,
            $destination,
            $amount,
            $description
        );

        $repository = new InMemoryTransactionRepository();

        $repository->add($transaction);

        $transactions = $repository->getAll();

        foreach ($transactions as $id => $result) {
            self::assertSame((string) $transaction->id(), $id);
            self::assertSame($result, $transaction);
        }
    }
}
