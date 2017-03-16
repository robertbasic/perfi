<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Exception;

class UnknownTransactionTypeException extends \InvalidArgumentException
{
    public static function withType(string $type) : self
    {
        $message = sprintf("The %s transaction type is unknown", $type);
        return new self($message);
    }
}
