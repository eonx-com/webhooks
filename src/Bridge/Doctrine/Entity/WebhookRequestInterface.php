<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity;

use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

interface WebhookRequestInterface
{
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
     * @param string $event
     * @param mixed[] $payload
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface $subscription
     *
     * @return void
     */
    public function populate(string $event, array $payload, SubscriptionInterface $subscription): void;
}
