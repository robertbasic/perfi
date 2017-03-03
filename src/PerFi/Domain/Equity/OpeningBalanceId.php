<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity;

use Ramsey\Uuid\Uuid;

class OpeningBalanceId
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
