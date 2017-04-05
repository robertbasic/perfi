<?php
declare(strict_types=1);

namespace PerFi\Application\Factory;

use PerFi\Domain\Account\Account;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionId;
use PerFi\Domain\Transaction\TransactionRecordDate;
use PerFi\Domain\Transaction\TransactionType;

class TransactionFactory
{
    /**
     * Create a Transaction from a row in the database
     *
     * @param array $transaction
     * @return Transaction
     */
    public static function fromArray(array $transaction, Account $sourceAccount, Account $destinationAccount) : Transaction
    {
        return Transaction::withId(
            TransactionId::fromString($transaction['transaction_id']),
            TransactionType::fromString($transaction['type']),
            $sourceAccount,
            $destinationAccount,
            MoneyFactory::centsInCurrency($transaction['amount'], $transaction['currency']),
            TransactionDate::fromString($transaction['date']),
            TransactionRecordDate::fromString($transaction['record_date']),
            $transaction['description'],
            (bool) $transaction['refunded']
        );
    }
}
