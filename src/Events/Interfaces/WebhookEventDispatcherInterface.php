<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Events\Interfaces;

interface WebhookEventDispatcherInterface
{
    /**
     * Dispatch an event.
     *
     * @param \EoneoPay\Webhook\Events\Interfaces\EventInterface $event
     *
     * @return array|null
     */
    public function dispatch(EventInterface $event): ?array;
}
