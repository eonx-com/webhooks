<?php declare(strict_types=1);

namespace EoneoPay\Webhook\Listeners\Interfaces;

interface WebhookEventListenerInterface
{
    /**
     * Handle an event.
     *
     * @param $event
     *
     * @return mixed|null
     */
    public function handle($event);
}
