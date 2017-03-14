<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFi\Domain\Account\Account;
use PerFi\Domain\Account\AccountRepository;
use PerFi\Domain\Transaction\Command\Pay;
use PerFi\Domain\Transaction\Command\PayFactory;

class PayFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @test
     */
    public function factory_creates_pay_command()
    {
        $source = 'fddf4716-6c0e-4f54-b539-d2d480a50d1a';
        $destination = '39e618b3-f58f-47bb-86f8-045767e7409c';
        $amount = '100';
        $currency = 'RSD';
        $description = 'supermarket';

        $sourceAccount = m::mock(Account::class);
        $destinationAccount = m::mock(Account::class);

        $accountRepository = m::mock(AccountRepository::class);
        $accountRepository->shouldReceive('get')
            ->twice()
            ->andReturn($sourceAccount, $destinationAccount);

        $factory = new PayFactory($accountRepository);
        $command = $factory($source, $destination, $amount, $currency, $description);

        self::assertInstanceOf(Pay::class, $command);
    }
}
