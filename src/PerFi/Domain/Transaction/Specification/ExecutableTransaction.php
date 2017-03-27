<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Transaction\Specification\PayableTransaction;
use PerFi\Domain\Transaction\Specification\RefundableTransaction;
use PerFi\Domain\Transaction\Transaction;

class ExecutableTransaction
{
    /**
     * @var PayableTransaction
     */
    private $payableTransactionSpecification;

    public function __construct()
    {
        $this->payableTransactionSpecification = new PayableTransaction();
        $this->refundableTransactionSpecification = new RefundableTransaction();
    }

    public function isSatisfiedBy(Transaction $transaction)
    {
        return $this->payableTransactionSpecification->isSatisfiedBy($transaction)
            || $this->refundableTransactionSpecification->isSatisfiedBy($transaction);
    }
}
