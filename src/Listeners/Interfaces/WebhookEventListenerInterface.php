<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Listeners\Interfaces;

use EoneoPay\Webhooks\Events\Interfaces\WebhookEventInterface;

interface WebhookEventListenerInterface
{
    /**
     * Handle a webhook event.
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\WebhookEventInterface $event
     * @return mixed
     */
    public function handle(WebhookEventInterface $event);
}
