<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Persister\Interfaces;

use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;
use Psr\Http\Message\ResponseInterface;

interface WebhookPersisterInterface
{
    /**
     * Creates and saves an individual WebhookRequest based on an activity, payload
     * and subscription (to that activity).
     *
     * Returns a sequence number that will be used with the payload.
     *
     * @param \EoneoPay\Webhooks\Model\ActivityInterface $activity
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface $subscription
     *
     * @return int
     */
    public function saveRequest(ActivityInterface $activity, SubscriptionInterface $subscription): int;

    /**
     * Updates the WebhookRequest identified by a sequence number with a response
     * that was received for the specific Request.
     *
     * @param int $sequence
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return void
     */
    public function saveResponse(int $sequence, ResponseInterface $response): void;
}
