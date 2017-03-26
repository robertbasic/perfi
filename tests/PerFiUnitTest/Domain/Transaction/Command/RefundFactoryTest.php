<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\MoneyFactory;
use PerFi\Domain\Transaction\Command\Refund;
use PerFi\Domain\Transaction\Command\RefundFactory;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionRepository;

class RefundFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function factory_creates_refund_command()
    {
        $transactionId = 'fddf4716-6c0e-4f54-b539-d2d480a50d1a';

        $transaction = m::mock(Transaction::class);
        $transaction->shouldReceive('destinationAccount')
            ->andReturn(m::mock(Account::class));
        $transaction->shouldReceive('sourceAccount')
            ->andReturn(m::mock(Account::class));
        $transaction->shouldReceive('amount')
            ->andReturn(MoneyFactory::amountInCurrency('500', 'RSD'));
        $transaction->shouldReceive('description');

        $transactionRepository = m::mock(TransactionRepository::class);
        $transactionRepository->shouldReceive('get')
            ->once()
            ->andReturn($transaction);

        $factory = new RefundFactory($transactionRepository);
        $command = $factory($transactionId);

        self::assertInstanceOf(Refund::class, $command);
    }
}
