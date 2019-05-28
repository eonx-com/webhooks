<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Listeners;

use EoneoPay\Webhooks\Bridge\Laravel\Events\ActivityCreatedEvent;
use EoneoPay\Webhooks\Bridge\Laravel\Exceptions\ActivityNotFoundException;
use EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\WebhookManagerInterface;

final class ActivityCreatedListener
{
    /**
     * @var \EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface
     */
    private $persister;

    /**
     * @var \EoneoPay\Webhooks\Webhooks\Interfaces\WebhookManagerInterface
     */
    private $webhookManager;

    /**
     * Constructor
     *
     * @param \EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface $persister
     * @param \EoneoPay\Webhooks\Webhooks\Interfaces\WebhookManagerInterface $webhookManager
     */
    public function __construct(
        ActivityPersisterInterface $persister,
        WebhookManagerInterface $webhookManager
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
