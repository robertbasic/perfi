<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Account;

use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\AccountTypeView;

class AccountTypeViewTest extends TestCase
{
    /**
     * @test
     */
    public function types_available_for_view()
    {
        $types = AccountTypeView::getTypes();

        $expected = [
            'Asset' => 'asset',
            'Expense' => 'expense',
            'Income' => 'income',
        ];

        self::assertSame($expected, $types);
    }
}
