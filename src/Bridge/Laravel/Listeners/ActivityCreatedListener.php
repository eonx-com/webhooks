<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Listeners;

use EoneoPay\Webhooks\Bridge\Laravel\Events\ActivityCreatedEvent;
use EoneoPay\Webhooks\Bridge\Laravel\Exceptions\ActivityNotFoundException;
use EoneoPay\Webhooks\Persisters\Interfaces\ActivityPersisterInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestFactoryInterface;

/**
 * This listener will listen for events created by the Laravel Bridge's EventDispatcher
 * and call out to services that expect to be notified when the event is raised.
 *
 * This class will be run as the entry point inside a worker because of the
 * Bridge's ActivityCreatedEvent's implementation of ShouldQueue.
 */
final class ActivityCreatedListener
{
    /**
     * @var \EoneoPay\Webhooks\Persisters\Interfaces\ActivityPersisterInterface
     */
    private $persister;

    /**
     * @var \EoneoPay\Webhooks\Webhooks\Interfaces\RequestFactoryInterface
     */
    private $webhookManager;

    /**
     * Constructor.
     *
     * @param \EoneoPay\Webhooks\Persisters\Interfaces\ActivityPersisterInterface $persister
     * @param \EoneoPay\Webhooks\Webhooks\Interfaces\RequestFactoryInterface $webhookManager
     */
    public function __construct(
        ActivityPersisterInterface $persister,
        RequestFactoryInterface $webhookManager
    ) {
        $this->persister = $persister;
        $this->webhookManager = $webhookManager;
    }

    /**
     * Handles the ActivityCreated event, dispatching it back to the Webhook process.
     *
     * @param \EoneoPay\Webhooks\Bridge\Laravel\Events\ActivityCreatedEvent $event
     *
     * @return void
     */
    public function handle(ActivityCreatedEvent $event): void
    {
        $activity = $this->persister->get($event->getActivityId());

        if ($activity === null) {
            throw new ActivityNotFoundException(\sprintf(
                'No activity was found when querying for activity "%s"',
                $event->getActivityId()
            ));
        }

        $this->webhookManager->processActivity($activity);
    }
}
