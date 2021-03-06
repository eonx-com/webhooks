<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Models;

use DateTime;
use EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface;

interface WebhookRequestInterface
{
    /**
     * Returns the activity that raised this webhook request.
     *
     * @return \EoneoPay\Webhooks\Models\ActivityInterface
     */
    public function getActivity(): ActivityInterface;

    /**
     * Returns the date time when this request was created.
     *
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?DateTime;

    /**
     * Returns unique request identifier.
     *
     * @return string
     */
    public function getExternalId(): string;

    /**
     * Returns the format the request should be serialized into.
     *
     * @return string
     */
    public function getRequestFormat(): string;

    /**
     * Returns the headers to be added to the request.
     *
     * @return string[]
     */
    public function getRequestHeaders(): array;

    /**
     * Returns the HTTP method to be used.
     *
     * @return string
     */
    public function getRequestMethod(): string;

    /**
     * Returns the URL that the request will be sent to.
     *
     * @return string
     */
    public function getRequestUrl(): string;

    /**
     * Returns the sequence number for this webhook request.
     *
     * In an implementation, typically this would be the
     * autoincrement primary key.
     *
     * @return int|null
     */
    public function getSequence(): ?int;

    /**
     * Populates a WebhookRequest with data.
     *
     * @param \EoneoPay\Webhooks\Models\ActivityInterface $activity
     * @param \EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface $subscription
     *
     * @return void
     */
    public function populate(ActivityInterface $activity, SubscriptionInterface $subscription): void;

    /**
     * Set created at date on the webhook.
     *
     * @param \DateTime $createdAt
     *
     * @return void
     */
    public function setCreatedAt(DateTime $createdAt): void;
}
