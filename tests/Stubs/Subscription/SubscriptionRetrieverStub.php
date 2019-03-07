<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Subscription;

use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionRetrieverInterface;

class SubscriptionRetrieverStub implements SubscriptionRetrieverInterface
{
    /**
     * @inheritdoc
     */
    public function getSubscriptionsForSubscribers(string $event, array $subscribers): array
    {
        return \array_map(function (): SubscriptionInterface {
            return new SubscriptionStub();
        }, $subscribers);
    }
}
