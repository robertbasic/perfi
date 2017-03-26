<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\Command;

use PerFi\Domain\Transaction\Command\Refund;
use PerFi\Domain\Transaction\TransactionId;
use PerFi\Domain\Transaction\TransactionRepository;

class RefundFactory
{
    /**
     * @var TransactionRepository
     */
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Create a Refund command
     *
     * @param string $transactionId
     * @return Refund
     */
    public function __invoke(string $transactionId) : Refund
    {
        $transactionId = TransactionId::fromString($transactionId);

        $transaction = $this->transactionRepository->get($transactionId);

        $refundCommand = new Refund($transaction);

        return $refundCommand;
    }
}
