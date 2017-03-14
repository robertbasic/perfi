<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Command;

use PerFi\Domain\Account\AccountId;
use PerFi\Domain\Account\AccountRepository;
use PerFi\Domain\Transaction\Command\Pay;

class PayFactory
{
    /**
     * @var AccountRepository
     */
    private $accountRepository;

    /**
     * Factory to create a Pay command
     *
     * @param AccountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Create a Pay command
     *
     * @param string $sourceAccountId
     * @param string $destinationAccountId
     * @param string $amount
     * @param string $currency
     * @param string $description
     * @return Pay
     */
    public function __invoke(
        string $sourceAccountId,
        string $destinationAccountId,
        string $amount,
        string $currency,
        string $description
    ) : Pay
    {
        $sourceAccountId = AccountId::fromString($sourceAccountId);
        $destinationAccountId = AccountId::fromString($destinationAccountId);

        $sourceAccount = $this->accountRepository->get($sourceAccountId);
        $destinationAccount = $this->accountRepository->get($destinationAccountId);

        $payCommand = new Pay(
            $sourceAccount,
            $destinationAccount,
            $amount,
            $currency,
            $description
        );

        return $payCommand;
    }
}
