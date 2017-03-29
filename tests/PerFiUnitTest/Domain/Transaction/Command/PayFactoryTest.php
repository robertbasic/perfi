<?php
declare(strict_types=1);

namespace PerFiUnitTest\Domain\Transaction\Command;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use PerFiUnitTest\Traits\AccountTrait;
use PerFi\Domain\Transaction\Command\Pay;
use PerFi\Domain\Transaction\Command\PayFactory;

class PayFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    use AccountTrait;

    /**
     * @test
     */
    public function factory_creates_pay_command()
    {
        $source = 'fddf4716-6c0e-4f54-b539-d2d480a50d1a';
        $destination = '39e618b3-f58f-47bb-86f8-045767e7409c';
        $amount = '100';
        $currency = 'RSD';
        $date = '2017-03-12';
        $description = 'supermarket';

        $sourceAccount = $this->mockAccount();
        $destinationAccount = $this->mockAccount();

        $accountRepository = $this->mockAccountRepository($sourceAccount, $destinationAccount);

        $factory = new PayFactory($accountRepository);
        $command = $factory($source, $destination, $amount, $currency, $date, $description);

        self::assertInstanceOf(Pay::class, $command);
    }
}
