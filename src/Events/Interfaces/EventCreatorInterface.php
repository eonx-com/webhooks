<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Events\Interfaces;

use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

interface EventCreatorInterface
{
    /**
     * Creates a new Event instance.
     *
     * @param string $event
     * @param int $sequence
     * @param mixed[] $payload
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface $subscription
     *
     * @return \EoneoPay\Webhooks\Events\Interfaces\EventInterface
     */
    public function create(
        string $event,
        int $sequence,
        array $payload,
        SubscriptionInterface $subscription
    ): EventInterface;
}
