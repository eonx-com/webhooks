<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Subscription\Interfaces;

interface SubscriptionRetrieverInterface
{
    /**
     * Retrieves any active subscriptions for the specified activity
     * that are subscribed by the array of WebhookSubscriber instances.
     *
     * @param string $activityConstant The activity constant
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriberInterface[] $subscribers Limited to these subscribers
     *
     * @return \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface[] Active webhook subscriptions
     */
    public function getSubscriptionsForSubscribers(string $activityConstant, array $subscribers): array;
}
