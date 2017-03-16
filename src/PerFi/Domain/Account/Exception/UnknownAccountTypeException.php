<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\Exception;

class UnknownAccountTypeException extends \InvalidArgumentException
{
    public function __construct(string $type)
    {
        $message = sprintf("The %s account type is unknown", $type);
        parent::__construct($message);
    }
}
