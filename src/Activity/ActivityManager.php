<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Activity;

use EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Activity\Interfaces\ActivityDataInterface;
use EoneoPay\Webhooks\Activity\Interfaces\ActivityManagerInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventCreatorInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionRetrieverInterface;

final class ActivityManager implements ActivityManagerInterface
{
    /**
     * @var \EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\EventCreatorInterface
     */
    private $eventCreator;

    /**
     * @var \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionRetrieverInterface
     */
    private $retriever;

    /**
     * Create Marshaller
     *
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionRetrieverInterface $retriever
     * @param \EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface $dispatcher
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventCreatorInterface $eventCreator
     */
    public function __construct(
        SubscriptionRetrieverInterface $retriever,
        EventDispatcherInterface $dispatcher,
        EventCreatorInterface $eventCreator
    ) {
        $this->retriever = $retriever;
        $this->dispatcher = $dispatcher;
        $this->eventCreator = $eventCreator;
    }

    /**
     * {@inheritdoc}
     */
    public function send(ActivityDataInterface $webhookData): void
    {
        $subscriptions = $this->retriever->getSubscriptionsForSubscribers(
            $webhookData->getEvent(),
            $webhookData->getSubscribers()
        );

        foreach ($subscriptions as $subscription) {
            $event = $this->eventCreator->create(
                $webhookData->getEvent(),
                $webhookData->getPayload(),
                $subscription
            );

            $this->dispatcher->dispatch($event);
        }
    }
}
