<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Listeners\Interfaces;

use EoneoPay\Webhooks\Events\Interfaces\EventInterface;

interface WebhookEventListenerInterface
{
    /**
     * Handle a webhook event.
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     *
     * @return mixed
     */
    public function handle(EventInterface $event);
}
