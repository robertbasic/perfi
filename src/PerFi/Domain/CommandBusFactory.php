<?php
declare(strict_types=1);

namespace PerFi\Domain;

use PerFi\Domain\Account\CommandHandler\CreateAccount as CreateAccountHandler;
use PerFi\Domain\Account\Command\CreateAccount as CreateAccountCommand;
use PerFi\Domain\Equity\CommandHandler\StartOpeningBalance as StartOpeningBalanceHandler;
use PerFi\Domain\Equity\Command\StartOpeningBalance as StartOpeningBalanceCommand;
use PerFi\Domain\Transaction\CommandHandler\ExecuteTransaction as ExecuteTransactionHandler;
use PerFi\Domain\Transaction\Command\Charge;
use PerFi\Domain\Transaction\Command\Pay;
use PerFi\Domain\Transaction\Command\PayBack;
use PerFi\Domain\Transaction\Command\Refund;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;

class CommandBusFactory
{
    /**
     * Configure and get a command bus that is ready to handle commands
     *
     * For now the command handlers and the service locator are
     * hard-coded in here, but they should come from a container-interop
     * a la Zend ServiceManager.
     *
     * @return MessageBusSupportingMiddleware
     */
    public static function getCommandBus() : MessageBusSupportingMiddleware
    {
        $commandHandlersByCommandName = [
            Pay::class => ExecuteTransactionHandler::class,
            Refund::class => ExecuteTransactionHandler::class,
            Charge::class => ExecuteTransactionHandler::class,
            PayBack::class => ExecuteTransactionHandler::class,
            CreateAccountCommand::class => CreateAccountHandler::class,
            StartOpeningBalanceCommand::class => StartOpeningBalanceHandler::class,
        ];

        $serviceLocator = function ($serviceId) {
            return new $serviceId();
        };

        $commandHandlerMap = new CallableMap(
            $commandHandlersByCommandName,
            new ServiceLocatorAwareCallableResolver($serviceLocator)
        );
        $commandNameResolver = new ClassBasedNameResolver();
        $commandHandlerResolver = new NameBasedMessageHandlerResolver(
            $commandNameResolver,
            $commandHandlerMap
        );

        $commandBus = new MessageBusSupportingMiddleware();
        $commandBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
        $commandBus->appendMiddleware(
            new DelegatesToMessageHandlerMiddleware(
                $commandHandlerResolver
            )
        );

        return $commandBus;
    }
}
