<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

class TransactionDate extends \DateTimeImmutable
{
    public static function now()
    {
        $timezone = new \DateTimeZone('UTC');

        return new self('now', $timezone);
    }
}
