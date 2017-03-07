<?php
declare(strict_types=1);

namespace PerFi\Domain\Account;

use Ramsey\Uuid\Uuid;

class AccountId
{
    /**
     * @var Uuid
     */
    private $id;

    /**
     * Create an account ID
     *
     * @param Uuid $id
     */
    private function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    /**
     * Create an account ID from an UUID
     *
     * @param Uuid $id
     * @return AccountId
     */
    public static function fromUuid(Uuid $id) : self
    {
        return new self($id);
    }

    /**
     * String representation of the account ID
     *
     * @return string
     */
    public function __toString() : string
    {
        return (string) $this->id;
    }
}
