<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Exception;

class UnknownTransactionTypeException extends \InvalidArgumentException
{
    public function __construct(string $type)
    {
        $message = sprintf("The %s transaction type is unknown", $type);
        parent::__construct($message);
    }
}
