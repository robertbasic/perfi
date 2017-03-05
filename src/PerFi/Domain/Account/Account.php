<?php
declare(strict_types=1);

namespace PerFi\Domain\Account;

use Money\Money;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Account
{
    /**
     * @var AccountId
     */
    private $id;

    /**
     * @var AccountType
     */
    private $type;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $amounts;

    private function __construct(AccountId $id, AccountType $type, string $title)
    {
        $this->id = $id;
        $this->type = $type;
        $this->title = $title;
    }

    public static function byStringType(string $type, string $title) : self
    {
        $id = AccountId::fromUuid(Uuid::uuid4());
        $type = AccountType::fromString($type);

        Assert::stringNotEmpty($title);

        return new self(
            $id,
            $type,
            $title
        );
    }

    public function balances() : array
    {
        $balances = [];

        foreach ($this->amounts as $currency => $amounts) {
            $balance = MoneyFactory::amountInCurrency('0', $currency);

            foreach ($amounts as $amount) {
                if ($currency === (string) $amount->getCurrency()) {
                    $balance = $balance->add($amount);
                }
            }

            $balances[$currency] = $balance;
        }

        return $balances;
    }

    public function debit(Money $amount)
    {
        $amount = $amount->absolute();
        $this->amounts[(string) $amount->getCurrency()][] = $amount;
    }

    public function credit(Money $amount)
    {
        $amount = $amount->multiply(-1);
        $this->amounts[(string) $amount->getCurrency()][] = $amount;
    }

    public function id() : AccountId
    {
        return $this->id;
    }

    public function title()
    {
        return $this->title;
    }

    public function type()
    {
        return $this->type;
    }

    public function __toString()
    {
        return sprintf("%s, %s", $this->title(), $this->type());
    }
}
