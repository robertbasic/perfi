<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

use Ramsey\Uuid\Uuid;

class TransactionId
{
    private $id;

    private function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    public static function fromUuid(Uuid $id) : self
    {
        return new self($id);
    }

    public function __toString() : string
    {
        return (string) $this->id;
    }
}
