<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Events\Interfaces;

interface WebhookEventDispatcherInterface
{
    /**
     * Dispatch an event.
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     *
     * @return void
     */
    public function dispatch(EventInterface $event): void;
}
