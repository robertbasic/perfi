<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity;

use Money\Money;
use PerFi\Domain\Equity\OpeningBalanceId;
use Ramsey\Uuid\Uuid;

class OpeningBalance
{
    /**
     * @var OpeningBalanceId
     */
    private $id;

    /**
     * @var Money
     */
    private $amount;

    /**
     * Create an opening balance
     *
     * @param OpeningBalanceId $id
     * @param Money $amount
     */
    private function __construct(OpeningBalanceId $id, Money $amount)
    {
        $this->id = $id;
        $this->amount = $amount;
    }

    /**
     * Create an opening balance with the starting amount
     *
     * @param Money $amount
     * @return OpeningBalance
     */
    public static function forStarting(Money $startAmount) : self
    {
        $id = OpeningBalanceId::fromUuid(Uuid::uuid4());

        return new self($id, $startAmount);
    }

    /**
     * Get the ID of the opening balance
     *
     * @return OpeningBalanceId
     */
    public function id() : OpeningBalanceId
    {
        return $this->id;
    }

    /**
     * Get the value of the opening balance
     *
     * @return string
     */
    public function value() : string
    {
        return (string) intval($this->amount->getAmount() / 100);
    }

    /**
     * Get the amount of the opening balance
     *
     * @return Money
     */
    public function amount() : Money
    {
        return $this->amount;
    }

    /**
     * Get the currency of the opening balance
     *
     * @return string
     */
    public function currency() : string
    {
        return (string) $this->amount()->getCurrency();
    }
}
