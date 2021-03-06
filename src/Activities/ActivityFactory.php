<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Activities;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface;
use EoneoPay\Webhooks\Activities\Interfaces\ActivityFactoryInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Payloads\Interfaces\PayloadManagerInterface;
use EoneoPay\Webhooks\Persisters\Interfaces\ActivityPersisterInterface;

final class ActivityFactory implements ActivityFactoryInterface
{
    /**
     * @var \EoneoPay\Webhooks\Persisters\Interfaces\ActivityPersisterInterface
     */
    private $activityPersister;

    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var \EoneoPay\Webhooks\Payloads\Interfaces\PayloadManagerInterface
     */
    private $payloadManager;

    /**
     * Constructor.
     *
     * @param \EoneoPay\Webhooks\Persisters\Interfaces\ActivityPersisterInterface $activityPersister
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface $eventDispatcher
     * @param \EoneoPay\Webhooks\Payloads\Interfaces\PayloadManagerInterface $payloadManager
     */
    public function __construct(
        ActivityPersisterInterface $activityPersister,
        EventDispatcherInterface $eventDispatcher,
        PayloadManagerInterface $payloadManager
    ) {
        $this->activityPersister = $activityPersister;
        $this->eventDispatcher = $eventDispatcher;
        $this->payloadManager = $payloadManager;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function send(ActivityDataInterface $activityData, ?\DateTime $now = null): void
    {
        $payload = $this->payloadManager->buildPayload($activityData);

        $activityId = $this->activityPersister->save(
            $activityData::getActivityKey(),
            $activityData->getPrimaryEntity(),
            $now ?? new DateTime('now'),
            $payload
        );

        $this->eventDispatcher->dispatchActivityCreated($activityId);
    }
}
