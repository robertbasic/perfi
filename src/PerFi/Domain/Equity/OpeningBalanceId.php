<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity;

use Ramsey\Uuid\Uuid;

class OpeningBalanceId
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * Create an opening balance ID
     *
     * @param Uuid $id
     */
    private function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    /**
     * Create an opening balance ID from an UUID
     *
     * @param Uuid $id
     * @return OpeningBalanceId
     */
    public static function fromUuid(Uuid $id) : self
    {
        return new self($id);
    }

    /**
     * String representation of the opening balance ID
     *
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->id;
    }
}
