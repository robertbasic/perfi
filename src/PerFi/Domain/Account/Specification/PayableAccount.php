<?php
declare(strict_types=1);

namespace PerFi\Domain\Account\Specification;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\Specification\AssetAccount;
use PerFi\Domain\Account\Specification\ExpenseAccount;

class PayableAccount
{
    /**
     * @var AssetAccount
     */
    private $assetAccountSpecification;

    /**
     * @var ExpenseAccount
     */
    private $expenseAccountSpecification;

    public function __construct()
    {
        $this->assetAccountSpecification = new AssetAccount();
        $this->expenseAccountSpecification = new ExpenseAccount();
    }

    public function isSatisfiedBy(Account $assetAccount, Account $expenseAccount)
    {
        return $this->assetAccountSpecification->isSatisfiedBy($assetAccount->type())
            && $this->expenseAccountSpecification->isSatisfiedBy($expenseAccount->type());
    }
}
