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

    public function isSatisfiedBy(
        TransactionType $transactionType,
        Account $sourceAccount,
        Account $destinationAccount
    )
    {
        return $this->payableTransactionSpecification->isSatisfiedBy($transactionType, $sourceAccount, $destinationAccount)
            || $this->refundableTransactionSpecification->isSatisfiedBy($transactionType, $sourceAccount, $destinationAccount);
    }
}
