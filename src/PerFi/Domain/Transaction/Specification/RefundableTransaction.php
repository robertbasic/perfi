<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\Specification\RefundableAccount;
use PerFi\Domain\Transaction\Specification\RefundTransaction;
use PerFi\Domain\Transaction\TransactionType;

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

    public function isSatisfiedBy(
        TransactionType $transactionType,
        Account $sourceAccount,
        Account $destinationAccount
    )
    {
        return $this->refundTransactionSpecification->isSatisfiedBy($transactionType)
            && $this->refundableAccountSpecification->isSatisfiedBy($sourceAccount, $destinationAccount);
    }
}
