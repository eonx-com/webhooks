<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Subscription\Interfaces;

use EoneoPay\Webhooks\Model\ActivityInterface;

interface SubscriptionResolverInterface
{
    /**
     * Resolves subscriptions for an activity.
     *
     * @param \EoneoPay\Webhooks\Model\ActivityInterface $activity
     *
     * @return \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface[]
     */
    public function resolveSubscriptions(ActivityInterface $activity): array;
}
