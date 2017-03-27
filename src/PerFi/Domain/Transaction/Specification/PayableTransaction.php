<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Specification;

use PerFi\Domain\Account\Specification\PayableAccount;
use PerFi\Domain\Transaction\Specification\PayTransaction;
use PerFi\Domain\Transaction\Transaction;

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

    public function isSatisfiedBy(Transaction $transaction)
    {
        return $this->payTransactionSpecification->isSatisfiedBy($transaction->type())
            && $this->payableAccountSpecification->isSatisfiedBy($transaction->sourceAccount(), $transaction->destinationAccount());
    }
}
