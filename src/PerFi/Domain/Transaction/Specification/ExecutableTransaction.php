<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\Specification\PayableTransaction;
use PerFi\Domain\Transaction\Specification\RefundableTransaction;
use PerFi\Domain\Transaction\TransactionType;

class ExecutableTransaction
{
    /**
     * @var PayableTransaction
     */
    private $payableTransactionSpecification;

    /**
     * @var RefundableTransaction
     */
    private $refundableTransactionSpecification;

    public function __construct()
    {
        $this->payableTransactionSpecification = new PayableTransaction();
        $this->refundableTransactionSpecification = new RefundableTransaction();
    }

    /**
     * Check if a transaction can be executed
     *
     * For a transaction to be executable, it either has to be a payable,
     * or a refundable transaction.
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
        return $this->payableTransactionSpecification->isSatisfiedBy($transactionType, $sourceAccount, $destinationAccount)
            || $this->refundableTransactionSpecification->isSatisfiedBy($transactionType, $sourceAccount, $destinationAccount);
    }
}
