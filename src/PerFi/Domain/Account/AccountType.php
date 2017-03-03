<?php
declare(strict_types=1);

namespace PerFi\Domain\Account;

use PerFi\Domain\Account\Exception\UnknownAccountTypeException;

class AccountType
{
    const ACCOUNT_TYPE_ASSET = 'asset';

    const ACCOUNT_TYPE_EXPENSE = 'expense';

    const ACCOUNT_TYPE_INCOME = 'income';

    const ACCOUNT_TYPES = [
        self::ACCOUNT_TYPE_ASSET,
        self::ACCOUNT_TYPE_EXPENSE,
        self::ACCOUNT_TYPE_INCOME,
    ];

    /**
     * @var string
     */
    private $type;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function fromString(string $type) : self
    {
        if (!in_array($type, self::ACCOUNT_TYPES)) {
            throw new UnknownAccountTypeException();
        }

        return new self(
            $type
        );
    }

    public function __toString() : string
    {
        return $this->type;
    }
}
