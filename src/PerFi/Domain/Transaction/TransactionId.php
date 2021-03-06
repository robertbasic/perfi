<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

use Ramsey\Uuid\Uuid;

class TransactionId
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * Create an transaction ID
     *
     * @param Uuid $id
     */
    private function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    /**
     * Create an transaction ID from an UUID
     *
     * @param Uuid $id
     * @return TransactionId
     */
    public static function fromUuid(Uuid $id) : self
    {
        return new self($id);
    }

    /**
     * Create a transaction ID from a string UUID
     *
     * @param string $id
     * @return AccountId
     */
    public static function fromString(string $id) : self
    {
        return new self(
            Uuid::fromString($id)
        );
    }

    /**
     * String representation of the transaction ID
     *
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->id;
    }
}
