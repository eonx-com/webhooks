<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Activity;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Activity\Interfaces\ActivityDataInterface;
use EoneoPay\Webhooks\Activity\Interfaces\ActivityManagerInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Payload\Interfaces\PayloadManagerInterface;
use EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface;

final class ActivityManager implements ActivityManagerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface
     */
    private $activityPersister;

    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var \EoneoPay\Webhooks\Payload\Interfaces\PayloadManagerInterface
     */
    private $payloadManager;

    /**
     * Constructor
     *
     * @param \EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface $activityPersister
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface $eventDispatcher
     * @param \EoneoPay\Webhooks\Payload\Interfaces\PayloadManagerInterface $payloadManager
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
            $activityData::getActivityConstant(),
            $now ?? new DateTime('now'),
            $payload
        );

        $this->eventDispatcher->activityCreated($activityId);
    }
}
