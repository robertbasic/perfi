<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

class TransactionRecordDate extends \DateTimeImmutable
{
    /**
     * Create a new immutable transaction date and time
     *
     * Denotes the date and time at which the transaction was recorded.
     * The timezone is UTC.
     *
     * @return TransactionDate
     */
    public static function now() : self
    {
        $timezone = new \DateTimeZone('UTC');

        return new self('now', $timezone);
    }
}
