<?php
declare(strict_types=1);

namespace PerFi\Domain\Account;

use PerFi\Domain\Account\Exception\UnknownAccountTypeException;
use Webmozart\Assert\Assert;

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

    /**
     * Create an account type
     *
     * @param string $type
     */
    private function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Create an account type from a string
     *
     * @param string $type
     * @return AccountType
     */
    public static function fromString(string $type) : self
    {
        Assert::stringNotEmpty($type, "The account type must be provided");

        if (!in_array($type, self::ACCOUNT_TYPES)) {
            throw UnknownAccountTypeException::withType($type);
        }

        return new self(
            $type
        );
    }

    /**
     * String representation of the account type
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->type;
    }
}
