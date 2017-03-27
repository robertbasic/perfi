<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\Specification\AssetAccount;
use PerFi\Domain\Account\Specification\ExpenseAccount;
use PerFi\Domain\Transaction\Specification\PayTransaction;
use PerFi\Domain\Transaction\TransactionType;

class PayableTransaction
{
    /**
     * @var AssetAccount
     */
    private $assetAccountSpecification;

    /**
     * @var ExpenseAccount
     */
    private $expenseAccountSpecification;

    /**
     * @var PayTransaction
     */
    private $payTransactionSpecification;

    public function __construct()
    {
        $this->assetAccountSpecification = new AssetAccount();
        $this->expenseAccountSpecification = new ExpenseAccount();
        $this->payTransactionSpecification = new PayTransaction();
    }

    /**
     * A transaction is payable if the transaction is of pay type,
     * the source account is as an asset account,
     * and the destination account is an expense account.
     *
     * @param TransactionType $transactionType
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     * @return bool
     */
    public function isSatisfiedBy(
        TransactionType $transactionType,
        Account $sourceAccount,
        Account $destinationAccount
    ) : bool
    {
        return $this->payTransactionSpecification->isSatisfiedBy($transactionType)
            && $this->assetAccountSpecification->isSatisfiedBy($sourceAccount)
            && $this->expenseAccountSpecification->isSatisfiedBy($destinationAccount);
    }
}
