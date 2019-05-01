<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Webhooks;

use EoneoPay\Webhooks\Webhook\Interfaces\WebhookDataInterface;

class WebhookDataStub implements WebhookDataInterface
{
    /**
     * @var string
     */
    private $event;

    /**
     * @var mixed[]
     */
    private $payload;

    /**
     * @var \EoneoPay\Webhooks\Subscription\Interfaces\SubscriberInterface[]
     */
    private $subscribers;

    /**
     * WebhookDataStub constructor.
     *
     * @param string $event
     * @param mixed[] $payload
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriberInterface[] $subscribers
     */
    public function __construct(string $event, array $payload, array $subscribers)
    {
        $this->event = $event;
        $this->payload = $payload;
        $this->subscribers = $subscribers;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribers(): array
    {
        return $this->subscribers;
    }
}
