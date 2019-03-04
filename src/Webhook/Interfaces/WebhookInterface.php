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
     * @param string $eventConstant
     * @param int $sequence
     * @param mixed[] $payload
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriberInterface[] $subscribers
     *
     * @return void
     */
    public function send(string $eventConstant, int $sequence, array $payload, array $subscribers): void;
}
