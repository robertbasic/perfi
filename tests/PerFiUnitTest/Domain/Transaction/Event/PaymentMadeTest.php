<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Event;

use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Transaction\Event\PaymentMade;

class PaymentMadeTest extends TestCase
{
    use TransactionTrait;

    /**
     * @test
     */
    public function transaction_is_set_on_event()
    {
        $transaction = $this->payTransaction();

        $event = new PaymentMade($transaction);

        $result = $event->transaction();

        self::assertSame($transaction, $result);
    }
}
