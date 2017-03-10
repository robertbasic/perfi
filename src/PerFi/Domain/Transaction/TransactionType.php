<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

use PerFi\Domain\Transaction\Exception\UnknownTransactionTypeException;
use Webmozart\Assert\Assert;

class TransactionType
{
    const TRANSACTION_TYPE_PAY = 'pay';

    const TRANSACTION_TYPE_CHARGE = 'charge';

    const TRANSACTION_TYPE_REFUND = 'refund';

    const TRANSACTION_TYPE_PAY_BACK = 'payback';

    const TRANSACTION_TYPES = [
        self::TRANSACTION_TYPE_PAY,
        self::TRANSACTION_TYPE_CHARGE,
        self::TRANSACTION_TYPE_REFUND,
        self::TRANSACTION_TYPE_PAY_BACK
    ];

    /**
     * @var string
     */
    private $type;

    /**
     * Create a transaction type
     *
     * @param string $type
     */
    private function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * Create a transaction type from a string
     *
     * @param string $type
     * @return TransactionType
     */
    public static function fromString(string $type) : self
    {
        Assert::stringNotEmpty($type);

        if (!in_array($type, self::TRANSACTION_TYPES)) {
            throw new UnknownTransactionTypeException();
        }

        return new self(
            $type
        );
    }

    /**
     * String representation of the transaction type
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->type;
    }
}
