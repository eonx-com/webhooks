<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Subscriptions\Interfaces;

use EoneoPay\Webhooks\Models\ActivityInterface;

interface SubscriptionResolverInterface
{
    /**
     * Resolves subscriptions for an activity.
     *
     * @param \EoneoPay\Webhooks\Models\ActivityInterface $activity
     *
     * @return \EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface[]
     */
    public function resolveSubscriptions(ActivityInterface $activity): array;
}
