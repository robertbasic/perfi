<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account\Specification;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\AccountType;
use PerFi\Domain\Account\Specification\AssetAccount;

class AssetAccountTest extends TestCase
{

    /**
     * @var AssetAccount
     */
    private $specification;

    public function setup()
    {
        $this->specification = new AssetAccount();
    }

    /**
     * @test
     */
    public function asset_account_type_satisfies_specification()
    {
        $type = AccountType::fromString('asset');

        $result = $this->specification->isSatisfiedBy($type);

        self::assertTrue($result);
    }

    /**
     * @test
     */
    public function expense_account_type_does_not_satisfy_specification()
    {
        $type = AccountType::fromString('expense');

        $result = $this->specification->isSatisfiedBy($type);

        self::assertFalse($result);
    }
}
