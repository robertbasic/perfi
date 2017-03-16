<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

class TransactionDate extends \DateTimeImmutable
{
    /**
     * Create a new immutable transaction date
     * from the provided date string
     *
     * The timezone is UTC.
     *
     * @return TransactionDate
     */
    public static function fromString(string $date) : self
    {
        // @todo assert $date string is in a good format
        $timezone = new \DateTimeZone('UTC');

        return new self($date, $timezone);
    }
}
