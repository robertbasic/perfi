<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\TransactionTrait;
use PerFi\Domain\Transaction\Command\Refund;
use PerFi\Domain\Transaction\Command\RefundFactory;

class RefundFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use TransactionTrait;

    /**
     * @test
     */
    public function factory_creates_refund_command()
    {
        $transactionId = 'fddf4716-6c0e-4f54-b539-d2d480a50d1a';

        $transaction = $this->mockTransaction();
        $transaction->shouldReceive('sourceAccount')
            ->andReturn($this->mockAccount());
        $transaction->shouldReceive('destinationAccount')
            ->andReturn($this->mockAccount());
        $transaction->shouldReceive('amount')
            ->andReturn($this->amount('500', 'RSD'));
        $transaction->shouldReceive('description');

        $transactionRepository = $this->mockTransactionRepository($transaction);

        $factory = new RefundFactory($transactionRepository);
        $command = $factory($transactionId);

        self::assertInstanceOf(Refund::class, $command);
    }
}
