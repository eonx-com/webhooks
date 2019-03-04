<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Events;

use EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Events\Interfaces\WebhookEventDispatcherInterface;

final class WebhookEventDispatcher implements WebhookEventDispatcherInterface
{
    /**
     * @var \EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * EventDispatcher constructor.
     *
     * @param \EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Dispatch an event.
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     *
     * @return void
     */
    public function dispatch(EventInterface $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
