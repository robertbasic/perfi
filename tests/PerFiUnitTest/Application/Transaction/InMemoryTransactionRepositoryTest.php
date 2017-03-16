<?php
declare(strict_types=1);

namespace PerFiUnitTest\Application\Transaction;

use PHPUnit\Framework\TestCase;
use PerFi\Application\Transaction\InMemoryTransactionRepository;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionType;

class InMemoryTransactionRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function can_add_transaction_to_repository()
    {
        $type = TransactionType::fromString('pay');
        $asset = AccountType::fromString('asset');
        $expense = AccountType::fromString('expense');
        $source = Account::byTypeWithTitle($asset, 'Cash');
        $destination = Account::byTypeWithTitle($expense, 'Groceries');
        $amount = MoneyFactory::amountInCurrency('500', 'RSD');
        $date = TransactionDate::fromString('2017-03-12');
        $description = 'groceries for dinner';

        $transaction = Transaction::betweenAccounts(
            $type,
            $source,
            $destination,
            $amount,
            $date,
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
