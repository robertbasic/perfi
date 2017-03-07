<?php
declare(strict_types=1);

namespace PerFi\Domain;

use PerFi\Domain\Transaction\EventSubscriber\CreditSourceAccountWhenTransactionExecuted;
use PerFi\Domain\Transaction\EventSubscriber\DebitDestinationAccountWhenTransactionExecuted;
use PerFi\Domain\Transaction\Event\TransactionExecuted;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleBus\Message\Subscriber\Resolver\NameBasedMessageSubscriberResolver;

class EventBusFactory
{
    /**
     * Configure and get an event bus that is ready to handle events
     *
     * For now the event subscribers and the service locator are
     * hard-coded in here, but they should come from a container-interop
     * a la Zend ServiceManager.
     *
     * @return MessageBusSupportingMiddleware
     */
    public static function getEventBus() : MessageBusSupportingMiddleware
    {
        $eventSubscribersByEventName = [
            TransactionExecuted::class => [
                CreditSourceAccountWhenTransactionExecuted::class,
                DebitDestinationAccountWhenTransactionExecuted::class,
            ]
        ];

        $serviceLocator = function ($serviceId) {
            return new $serviceId();
        };

        $eventSubscriberCollection = new CallableCollection(
            $eventSubscribersByEventName,
            new ServiceLocatorAwareCallableResolver($serviceLocator)
        );
        $eventNameResolver = new ClassBasedNameResolver();
        $eventSubscribersResolver = new NameBasedMessageSubscriberResolver(
            $eventNameResolver,
            $eventSubscriberCollection
        );

        $eventBus = new MessageBusSupportingMiddleware();
        $eventBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
        $eventBus->appendMiddleware(
            new NotifiesMessageSubscribersMiddleware(
                $eventSubscribersResolver
            )
        );

        return $eventBus;
    }
}
