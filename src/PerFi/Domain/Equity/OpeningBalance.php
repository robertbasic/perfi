<?php
declare(strict_types=1);

namespace PerFi\Domain\Equity;

use Money\Money;
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

    private function __construct(OpeningBalanceId $id, Money $amount)
    {
        $this->id = $id;
        $this->amount = $amount;
    }

    public static function forStarting(Money $startAmount) : self
    {
        $id = OpeningBalanceId::fromUuid(Uuid::uuid4());

        return new self($id, $startAmount);
    }

    public function id()
    {
        return $this->id;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function currency()
    {
        return (string) $this->amount()->getCurrency();
    }
}
