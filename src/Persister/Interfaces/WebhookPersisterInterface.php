<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Persister\Interfaces;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entity\ActivityInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

interface WebhookPersisterInterface
{
    /**
     * Creates and saves an individual WebhookRequest based on an activity, payload
     * and subscription (to that activity).
     *
     * Returns a sequence number that will be used with the payload.
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\ActivityInterface $activity
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
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface $response
     *
     * @return void
     */
    public function saveResponse(int $sequence, ResponseInterface $response): void;
}
