<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\Specification\PayableAccount;
use PerFi\Domain\Transaction\Specification\PayTransaction;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionType;

class PayableTransaction
{
    /**
     * @var PayableAccount
     */
    private $payableAccountSpecification;

    /**
     * @var PayTransaction
     */
    private $payTransactionSpecification;

    public function __construct()
    {
        $this->payableAccountSpecification = new PayableAccount();
        $this->payTransactionSpecification = new PayTransaction();
    }

    public function isSatisfiedBy(
        TransactionType $transactionType,
        Account $sourceAccount,
        Account $destinationAccount
    )
    {
        return $this->payTransactionSpecification->isSatisfiedBy($transactionType)
            && $this->payableAccountSpecification->isSatisfiedBy($sourceAccount, $destinationAccount);
    }
}
