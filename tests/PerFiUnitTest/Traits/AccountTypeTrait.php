<?php
declare(strict_types=1);

namespace PerFiUnitTest\Traits;

use PerFi\Domain\Account\AccountType;

trait AccountTypeTrait
{
    public function asset() : AccountType
    {
        return AccountType::fromString('asset');
    }

    public function expense() : AccountType
    {
        return AccountType::fromString('expense');
    }
}
