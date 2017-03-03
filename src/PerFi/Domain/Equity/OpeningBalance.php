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

    public function id() : OpeningBalanceId
    {
        return $this->id;
    }

    public function value() : string
    {
        return (string) intval($this->amount->getAmount() / 100);
    }

    public function amount() : Money
    {
        return $this->amount;
    }

    public function currency() : string
    {
        return (string) $this->amount()->getCurrency();
    }
}
