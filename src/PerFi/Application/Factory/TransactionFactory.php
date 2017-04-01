<?php
declare(strict_types=1);

namespace PerFi\Application\Factory;

use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionId;
use PerFi\Domain\Transaction\TransactionRecordDate;
use PerFi\Domain\Transaction\TransactionType;
use PerFi\Application\Factory\AccountFactory;

class TransactionFactory
{
    /**
     * Create a Transaction from a row in the database
     *
     * @param array $transaction
     * @return Transaction
     */
    public static function fromArray(array $transaction) : Transaction
    {
        $sourceAccount = AccountFactory::fromArray(self::getSubArray($transaction, 'source_account'));
        $destinationAccount = AccountFactory::fromArray(self::getSubArray($transaction, 'destination_account'));

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

    private static function getSubArray(array $array, string $prefix) : array
    {
        $subArray = [];

        foreach ($array as $key => $value) {
            if (strpos($key, $prefix) === false) {
                continue;
            }

            $subArray[substr($key, strlen($prefix) + 1)] = $value;
        }

        return $subArray;
    }
}
