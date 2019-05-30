<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Subscription;

use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionResolverInterface;

/**
 * @coversNothing
 */
class SubscriptionResolverStub implements SubscriptionResolverInterface
{
    /**
     * @var \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface[]
     */
    private $subscriptions = [];

    /**
     * {@inheritdoc}
     */
    public function resolveSubscriptions(ActivityInterface $activity): array
    {
        return $this->subscriptions;
    }

    /**
     * Sets subscriptions
     *
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface[] $subscriptions
     *
     * @return void
     */
    public function setSubscriptions(array $subscriptions): void
    {
        $this->subscriptions = $subscriptions;
    }
}
