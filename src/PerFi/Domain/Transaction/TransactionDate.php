<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

class TransactionDate extends \DateTimeImmutable
{
    /**
     * Create a new immutable transaction date and time
     *
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
