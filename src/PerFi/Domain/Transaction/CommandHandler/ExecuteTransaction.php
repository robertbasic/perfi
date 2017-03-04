<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\CommandHandler;

use PerFi\Domain\Command;
use PerFi\Domain\CommandHandler;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionRepository;

class ExecuteTransaction implements CommandHandler
{

    /**
     * @var TransactionRepository
     */
    private $transactions;

    public function __construct(TransactionRepository $transactions)
    {
        $this->transactions = $transactions;
    }

    public function __invoke(Command $executeTransaction)
    {
        $transaction = $executeTransaction->payload();

        $this->transactions->add($transaction);

        // @todo These should be event triggers -> event listeners
        $sourceAccount = $transaction->sourceAccount();
        $sourceAccount->recordTransaction($transaction);

        $destinationAccount = $transaction->destinationAccount();
        $destinationAccount->recordTransaction($transaction);
    }
}
