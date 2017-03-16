<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction;

use PerFi\Domain\Transaction\Exception\UnknownTransactionTypeException;
use Webmozart\Assert\Assert;

class TransactionType
{
    const TRANSACTION_TYPE_PAY = 'pay';

    const TRANSACTION_TYPE_REFUND = 'refund';

    const TRANSACTION_TYPES = [
        self::TRANSACTION_TYPE_PAY,
        self::TRANSACTION_TYPE_REFUND,
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
        Assert::stringNotEmpty($type, "The transaction type must be provided");

        if (!in_array($type, self::TRANSACTION_TYPES)) {
            throw UnknownTransactionTypeException::withType($type);
        }

        return new self(
            $type
        );
    }

    /**
     * See if transaction type is pay
     *
     * @return bool
     */
    public function isPay() : bool
    {
        return $this->type === self::TRANSACTION_TYPE_PAY;
    }

    /**
     * See if transaction type is refund
     *
     * @return bool
     */
    public function isRefund() : bool
    {
        return $this->type === self::TRANSACTION_TYPE_REFUND;
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
