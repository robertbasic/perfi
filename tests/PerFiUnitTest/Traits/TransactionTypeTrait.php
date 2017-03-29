<?php
declare(strict_types=1);

namespace PerFiUnitTest\Traits;

use PerFi\Domain\Transaction\TransactionType;

trait TransactionTypeTrait
{
    public function pay() : TransactionType
    {
        return TransactionType::fromString('pay');
    }

    public function refund() : TransactionType
    {
        return TransactionType::fromString('refund');
    }
}
