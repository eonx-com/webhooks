<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Subscription;

use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionRetrieverInterface;

class SubscriptionRetrieverStub implements SubscriptionRetrieverInterface
{
    /**
     * @var \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface[]
     */
    private $toReturn = [];

    /**
     * @return \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface[]
     */
    public function getToReturn(): array
    {
        return $this->toReturn;
    }

    /**
     * @inheritdoc
     */
    public function getSubscriptionsForSubscribers(string $event, array $subscribers): array
    {
        return $this->toReturn;
    }

    /**
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface[] $toReturn
     *
     * @return void
     */
    public function setToReturn(array $toReturn): void
    {
        $this->toReturn = $toReturn;
    }
}
