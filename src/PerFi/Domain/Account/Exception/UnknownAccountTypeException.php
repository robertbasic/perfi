<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\Exception;

class UnknownAccountTypeException extends \InvalidArgumentException
{
    public static function withType(string $type) : self
    {
        $message = sprintf("The %s account type is unknown", $type);
        return new self($message);
    }
}
