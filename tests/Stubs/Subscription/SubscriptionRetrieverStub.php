<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Subscription;

use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionRetrieverInterface;

class SubscriptionRetrieverStub implements SubscriptionRetrieverInterface
{
    /**
     * {@inheritdoc}
     */
    public function getSubscriptionsForSubscribers(string $activity, array $subscribers): array
    {
        return \array_map(static function (): SubscriptionInterface {
            return new SubscriptionStub();
        }, $subscribers);
    }
}
