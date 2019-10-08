<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Subscriptions;

use EoneoPay\Webhooks\Models\ActivityInterface;
use EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionResolverInterface;

/**
 * @coversNothing
 */
class SubscriptionResolverStub implements SubscriptionResolverInterface
{
    /**
     * @var \EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface[]
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
     * Sets subscriptions.
     *
     * @param \EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface[] $subscriptions
     *
     * @return void
     */
    public function setSubscriptions(array $subscriptions): void
    {
        $this->subscriptions = $subscriptions;
    }
}
