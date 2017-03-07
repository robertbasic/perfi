<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\CommandHandler;

use PerFi\Domain\Command;
use PerFi\Domain\CommandHandler;
use PerFi\Domain\Transaction\Event\TransactionExecuted;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionRepository;
use SimpleBus\Message\Bus\MessageBus;

class ExecuteTransaction implements CommandHandler
{

    /**
     * @var TransactionRepository
     */
    private $transactions;

    /**
     * A handler that handles the execution of a transaction
     *
     * @param TransactionRepository $transactions
     * @param MessageBus $eventBus
     */
    public function __construct(TransactionRepository $transactions, MessageBus $eventBus)
    {
        $this->transactions = $transactions;
        $this->eventBus = $eventBus;
    }

    /**
     * Handle the execute transaction command
     *
     * Add the executed transaction to the repository.
     * Tell the event bus to handle the transaction executed event.
     *
     * @param Command $executeTransaction
     */
    public function __invoke(Command $executeTransaction)
    {
        $transaction = $executeTransaction->payload();

        $this->transactions->add($transaction);

        $event = new TransactionExecuted($transaction);

        $this->eventBus->handle($event);
    }
}
