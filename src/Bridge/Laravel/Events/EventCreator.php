<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Events;

use EoneoPay\Webhooks\Events\Interfaces\EventCreatorInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

final class EventCreator implements EventCreatorInterface
{
    /**
     * @var \EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface
     */
    private $persister;

    /**
     * Create the creator
     *
     * @param \EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface $persister
     */
    public function __construct(WebhookPersisterInterface $persister)
    {
        $this->persister = $persister;
    }

    /**
     * @inheritdoc
     */
    public function create(
        string $event,
        array $payload,
        SubscriptionInterface $subscription
    ): EventInterface {
        $sequence = $this->persister->save($event, $payload, $subscription);

        return new Event(
            $subscription->getUrl(),
            $sequence,
            $subscription->getSerializationFormat(),
            $subscription->getMethod(),
            $payload,
            $subscription->getHeaders()
        );
    }
}
