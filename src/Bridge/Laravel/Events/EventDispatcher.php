<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Events;

use EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface as RealEventDispatcher;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;

final class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var \EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * EventDispatcher constructor.
     *
     * @param \EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(RealEventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function activityCreated(int $activityId): void
    {
        $event = new ActivityCreatedEvent($activityId);

        $this->eventDispatcher->dispatch($event);
    }

    /**
     * {@inheritdoc}
     */
    public function webhookRequest(int $requestId): void
    {
        $event = new WebhookRequestCreatedEvent($requestId);

        $this->eventDispatcher->dispatch($event);
    }
}
