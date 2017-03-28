<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Account\Specification\AssetAccount;
use PerFi\Domain\Account\Specification\ExpenseAccount;
use PerFi\Domain\Transaction\Specification\RefundTransaction;
use PerFi\Domain\Transaction\Transaction;

class RefundableTransaction
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
     * @var RefundTransaction
     */
    private $refundTransactionSpecification;

    public function __construct()
    {
        $this->assetAccountSpecification = new AssetAccount();
        $this->expenseAccountSpecification = new ExpenseAccount();
        $this->refundTransactionSpecification = new RefundTransaction();
    }

    /**
     * A transaction is refundable if the transaction is of refund type,
     * the source account is as an expense account,
     * and the destination account is an asset account.
     *
     * @param TransactionType $transactionType
     * @param Account $sourceAccount
     * @param Account $destinationAccount
     * @return bool
     */
    public function isSatisfiedBy(Transaction $transaction) : bool
    {
        return $this->refundTransactionSpecification->isSatisfiedBy($transaction)
            && $this->expenseAccountSpecification->isSatisfiedBy($transaction->sourceAccount())
            && $this->assetAccountSpecification->isSatisfiedBy($transaction->destinationAccount());
    }
}
