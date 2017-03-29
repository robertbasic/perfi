<?php
declare(strict_types=1);

namespace PerFi\Domain\Transaction\CommandHandler;

use PerFi\Domain\Transaction\Command\Refund;
use PerFi\Domain\Transaction\Event\TransactionRefunded;
use PerFi\Domain\Transaction\Exception\TransactionAlreadyRefundedException;
use PerFi\Domain\Transaction\Exception\TransactionNotRefundableException;
use PerFi\Domain\Transaction\Specification\NotRefundedTransaction;
use PerFi\Domain\Transaction\Specification\RefundableTransaction;
use PerFi\Domain\Transaction\Transaction;
use PerFi\Domain\Transaction\TransactionRepository;
use SimpleBus\Message\Bus\MessageBus;

class ExecuteRefund
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
     * A handler that handles the execution of a transaction refund
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
     * Handle the refund command
     *
     * Add the refund transaction to the repository.
     * Tell the event bus to handle the transaction refunded event.
     *
     * @param Refund $command
     */
    public function __invoke(Refund $command)
    {
        $refundedTransaction = $command->transaction();

        $notRefundedSpecification = new NotRefundedTransaction();
        if (!$notRefundedSpecification->isSatisfiedBy($refundedTransaction)) {
            throw TransactionAlreadyRefundedException::withTransaction($refundedTransaction);
        }

        $refundTransaction = Transaction::betweenAccounts(
            $command->transactionType(),
            $command->sourceAccount(),
            $command->destinationAccount(),
            $command->amount(),
            $command->date(),
            $command->description()
        );

        $specification = new RefundableTransaction();
        if (!$specification->isSatisfiedBy($refundTransaction)) {
            throw TransactionNotRefundableException::withTransaction($refundTransaction);
        }

        $this->transactions->add($refundTransaction);

        $event = new TransactionRefunded($refundTransaction, $refundedTransaction);

        $this->eventBus->handle($event);
    }
}
