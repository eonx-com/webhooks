<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Listeners\Interfaces;

use EoneoPay\Webhook\Events\Interfaces\EventInterface;

interface WebhookEventListenerInterface
{
    /**
     * Handle a webhook event.
     *
     * @param \EoneoPay\Webhook\Events\Interfaces\EventInterface $event
     *
     * @return mixed
     */
    public function handle(EventInterface $event);
}
