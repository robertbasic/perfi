<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\CommandHandler;

use PerFi\Domain\Transaction\Command\Pay;
use PerFi\Domain\Transaction\Event\PaymentMade;
use PerFi\Domain\Transaction\Event\TransactionExecuted;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionRepository;
use SimpleBus\Message\Bus\MessageBus;

class ExecutePayment
{

    /**
     * @var TransactionRepository
     */
    private $transactions;

    /**
     * @var MessageBus
     */
    private $eventBus;

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
     * Handle the pay command
     *
     * Add the payment transaction to the repository.
     * Tell the event bus to handle the payment made event.
     *
     * @param Pay $command
     */
    public function __invoke(Pay $command)
    {
        $transaction = Transaction::betweenAccounts(
            $command->transactionType(),
            $command->sourceAccount(),
            $command->destinationAccount(),
            $command->amount(),
            $command->date(),
            $command->description()
        );

        $this->transactions->add($transaction);

        $event = new PaymentMade($transaction);

        $this->eventBus->handle($event);
    }
}
