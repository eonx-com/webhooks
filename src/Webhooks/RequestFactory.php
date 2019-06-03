<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionResolverInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestFactoryInterface;

class RequestFactory implements RequestFactoryInterface
{
    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionResolverInterface
     */
    private $subscriptionResolver;

    /**
     * @var \EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface
     */
    private $webhookPersister;

    /**
     * Constructor
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface $dispatcher
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionResolverInterface $subscriptionResolver
     * @param \EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface $webhookPersister
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        SubscriptionResolverInterface $subscriptionResolver,
        WebhookPersisterInterface $webhookPersister
    ) {
        $this->dispatcher = $dispatcher;
        $this->subscriptionResolver = $subscriptionResolver;
        $this->webhookPersister = $webhookPersister;
    }

    /**
     * {@inheritdoc}
     */
    public function processActivity(ActivityInterface $activity): void
    {
        $subscriptions = $this->subscriptionResolver->resolveSubscriptions($activity);

        foreach ($subscriptions as $subscription) {
            $requestId = $this->webhookPersister->saveRequest($activity, $subscription);

            $this->dispatcher->webhookRequestCreated($requestId);
        }
    }
}
