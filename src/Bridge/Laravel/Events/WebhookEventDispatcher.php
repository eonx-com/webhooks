<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Events;

use EoneoPay\Webhook\Events\Interfaces\EventInterface;
use EoneoPay\Webhook\Events\Interfaces\WebhookEventDispatcherInterface;
use Illuminate\Contracts\Events\Dispatcher as IlluminateEventDispatcher;

class WebhookEventDispatcher implements WebhookEventDispatcherInterface
{
    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    private $eventDispatcher;

    /**
     * EventDispatcher constructor.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $eventDispatcher
     */
    public function __construct(IlluminateEventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Dispatch an event.
     *
     * @param \EoneoPay\Webhook\Events\Interfaces\EventInterface $event
     *
     * @return array|null
     */
    public function dispatch(EventInterface $event): ?array
    {
        return $this->eventDispatcher->dispatch($event);
    }
}
