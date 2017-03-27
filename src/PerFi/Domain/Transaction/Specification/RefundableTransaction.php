<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Account\Specification\RefundableAccount;
use PerFi\Domain\Transaction\Specification\RefundTransaction;
use PerFi\Domain\Transaction\Transaction;

class RefundableTransaction
{
    /**
     * @var RefundableAccount
     */
    private $refundableAccountSpecification;

    /**
     * @var RefundTransaction
     */
    private $refundTransactionSpecification;

    public function __construct()
    {
        $this->refundableAccountSpecification = new RefundableAccount();
        $this->refundTransactionSpecification = new RefundTransaction();
    }

    public function isSatisfiedBy(Transaction $transaction)
    {
        return $this->refundTransactionSpecification->isSatisfiedBy($transaction->type())
            && $this->refundableAccountSpecification->isSatisfiedBy($transaction->sourceAccount(), $transaction->destinationAccount());
    }
}
