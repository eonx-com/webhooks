<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Persisters\Interfaces;

use EoneoPay\Webhooks\Models\ActivityInterface;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;
use EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

interface WebhookPersisterInterface
{
    /**
     * Creates and saves an individual WebhookRequest based on an activity, payload
     * and subscription (to that activity).
     *
     * Returns a sequence number that will be used with the payload.
     *
     * @param \EoneoPay\Webhooks\Models\ActivityInterface $activity
     * @param \EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface $subscription
     *
     * @return int
     */
    public function saveRequest(ActivityInterface $activity, SubscriptionInterface $subscription): int;

    /**
     * Updates the WebhookRequest identified by a sequence number with a response
     * that was received for the specific Request.
     *
     * @param \EoneoPay\Webhooks\Models\WebhookRequestInterface $webhookRequest
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return void
     */
    public function saveResponse(WebhookRequestInterface $webhookRequest, ResponseInterface $response): void;

    /**
     * Saves a WebhookResponse.
     *
     * @param \EoneoPay\Webhooks\Models\WebhookRequestInterface $webhookRequest
     * @param \Throwable $exception
     *
     * @return mixed
     */
    public function saveResponseException(
        WebhookRequestInterface $webhookRequest,
        Throwable $exception
    );
}
