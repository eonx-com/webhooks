<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Listeners\Interfaces;

use EoneoPay\Webhook\Events\Interfaces\WebhookEventInterface;

interface WebhookEventListenerInterface
{
    /**
     * Handle a webhook event.
     *
     * @param WebhookEventInterface $event
     * @return mixed
     */
    public function handle(WebhookEventInterface $event);
}
