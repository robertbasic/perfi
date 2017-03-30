<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\AccountTrait;
use PerFiUnitTest\Traits\AmountTrait;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Transaction\Command\Pay;
use PerFi\Domain\Transaction\TransactionDate;
use PerFi\Domain\Transaction\TransactionType;

class PayTest extends TestCase
{
    use AccountTrait;
    use AmountTrait;

    /**
     * @var Account
     */
    private $assetAccount;

    /**
     * @var Account
     */
    private $expenseAccount;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $description;

    public function setup()
    {
        $this->assetAccount = $this->assetAccount();
        $this->expenseAccount = $this->expenseAccount();
        $this->amount = '500';
        $this->currency = 'RSD';
        $this->date = '2017-03-12';
        $this->description = 'supermarket';
    }

    /**
     * @test
     */
    public function pay_transaction_command_is_created()
    {
        $command = new Pay(
            $this->assetAccount,
            $this->expenseAccount,
            $this->amount,
            $this->currency,
            $this->date,
            $this->description
        );

        $transactionType = $command->transactionType();
        $sourceAccount = $command->sourceAccount();
        $destinationAccount = $command->destinationAccount();
        $amount = $command->amount();
        $date = $command->date();
        $description = $command->description();

        $expectedAmount = $this->amount('500', 'RSD');

        self::assertInstanceOf(TransactionType::class, $transactionType);
        self::assertSame('pay', (string) $transactionType);

        self::assertInstanceOf(Account::class, $sourceAccount);
        self::assertInstanceOf(Account::class, $destinationAccount);

        self::assertSame('asset', (string) $sourceAccount->type());
        self::assertSame('expense', (string) $destinationAccount->type());

        self::assertTrue($expectedAmount->equals($amount));

        self::assertInstanceOf(TransactionDate::class, $date);

        self::assertSame('supermarket', $description);
    }
}
