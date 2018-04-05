<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Events\Interfaces;

interface WebhookEventDispatcherInterface
{
    /**
     * Fire an event and call the listeners.
     *
     * @param  string|mixed  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     *
     * @return array|null
     */
    public function dispatch($event, $payload = null, ?bool $halt = null): ?array;
}
