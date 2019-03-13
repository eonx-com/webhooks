<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Persister\Interfaces;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

interface WebhookPersisterInterface
{
    /**
     * Saves an event and returns the sequence number to be used
     * when actually sending.
     *
     * @param string $event
     * @param mixed[] $payload
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface $subscription
     *
     * @return int
     */
    public function save(string $event, array $payload, SubscriptionInterface $subscription): int;

    /**
     * Updates the sequence number that was persisted with a response.
     *
     * @param int $sequence
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface $response
     *
     * @return void
     */
    public function update(int $sequence, ResponseInterface $response): void;
}
