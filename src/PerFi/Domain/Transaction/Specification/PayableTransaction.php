<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Account\Specification\AssetAccount;
use PerFi\Domain\Account\Specification\ExpenseAccount;
use PerFi\Domain\Transaction\Specification\PayTransaction;
use PerFi\Domain\Transaction\Transaction;

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
     * it's source account is as an asset account,
     * and it's destination account is an expense account.
     *
     * @param Transaction $transaction
     * @return bool
     */
    public function isSatisfiedBy(Transaction $transaction) : bool
    {
        return $this->payTransactionSpecification->isSatisfiedBy($transaction->type())
            && $this->assetAccountSpecification->isSatisfiedBy($transaction->sourceAccount())
            && $this->expenseAccountSpecification->isSatisfiedBy($transaction->destinationAccount());
    }
}
