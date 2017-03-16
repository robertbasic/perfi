<?php
declare(strict_types=1);

namespace PerFi\Domain\Account;

use Money\Money;
use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\MoneyFactory;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Account implements \JsonSerializable
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

    /**
     * Create an account
     *
     * @param AccountId $id
     * @param AccountType $type
     * @param string $title
     */
    private function __construct(AccountId $id, AccountType $type, string $title)
    {
        Assert::stringNotEmpty($title, "The account title must be provided");

        $this->id = $id;
        $this->type = $type;
        $this->title = $title;

        $this->amounts = [];
    }

    /**
     * Create an account of a certain type with a title
     *
     * @param AccountType $type
     * @param string $title
     * @return Account
     */
    public static function byTypeWithTitle(AccountType $type, string $title) : self
    {
        $id = AccountId::fromUuid(Uuid::uuid4());

        return new self(
            $id,
            $type,
            $title
        );
    }

    /**
     * Create an existing account, with ID, type and title
     *
     * @param AccountId $id
     * @param AccountType $type
     * @param string $title
     * @return Account
     */
    public static function withId(AccountId $id, AccountType $type, string $title) : self
    {
        return new self(
            $id,
            $type,
            $title
        );
    }

    /**
     * Can this source account pay the destination account?
     *
     * @param Account $destinationAccount
     * @return bool
     */
    public function canPay(Account $destinationAccount) : bool
    {
        return $this->type()->isAsset() && $destinationAccount->type()->isExpense();
    }

    /**
     * Can this source account refund the destination account?
     *
     * @param Account $destinationAccount
     * @return bool
     */
    public function canRefund(Account $destinationAccount) : bool
    {
        return $this->type()->isExpense() && $destinationAccount->type()->isAsset();
    }

    /**
     * Calculate the balances for the account
     *
     * Calculates the balances for every currency separately.
     *
     * @return array
     */
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

    /**
     * Debit the account for the amount provided
     *
     * @param Money $amount
     */
    public function debit(Money $amount)
    {
        $amount = $amount->absolute();
        $this->amounts[(string) $amount->getCurrency()][] = $amount;
    }

    /**
     * Credit the account for the amount provided
     *
     * @param Money $amount
     */
    public function credit(Money $amount)
    {
        $amount = $amount->multiply(-1);
        $this->amounts[(string) $amount->getCurrency()][] = $amount;
    }

    /**
     * Get the ID of the account
     *
     * @return AccountId
     */
    public function id() : AccountId
    {
        return $this->id;
    }

    /**
     * Get the title of the account
     *
     * @return string
     */
    public function title() : string
    {
        return $this->title;
    }

    /**
     * Get the type of the account
     *
     * @return AccountType
     */
    public function type() : AccountType
    {
        return $this->type;
    }

    /**
     * String representation of the account
     *
     * @return string
     */
    public function __toString() : string
    {
        return sprintf("%s, %s", $this->title(), $this->type());
    }

    /**
     * JSON serializeable object
     *
     * @return array
     */
    public function jsonSerialize() : array
    {
        return [
            'id' => (string) $this->id(),
            'title' => $this->title(),
            'type' => (string) $this->type(),
        ];
    }
}
