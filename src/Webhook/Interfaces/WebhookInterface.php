<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhook\Interfaces;

interface WebhookInterface
{
    /**
     * The entry point for sending webhooks. This method will dispatch jobs
     * for hitting all webhook subscriptions that match the event and its
     * subscribers.
     *
     * @param \EoneoPay\Webhooks\Webhook\Interfaces\WebhookDataInterface $webhookData
     *
     * @return void
     */
    public function send(WebhookDataInterface $webhookData): void;
}
