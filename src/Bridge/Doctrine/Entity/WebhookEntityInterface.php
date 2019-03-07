<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity;

use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

interface WebhookEntityInterface
{
    /**
     * Returns the sequence number for this webhook call.
     *
     * In an implementation, typically this would be the
     * autoincrement primary key.
     *
     * @return int
     */
    public function getSequence(): int;

    /**
     * Populates a WebhookEntity with data.
     *
     * @param string $event
     * @param mixed[] $payload
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface $subscription
     *
     * @return void
     */
    public function populate(string $event, array $payload, SubscriptionInterface $subscription): void;
}
