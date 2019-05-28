<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Persister;

use DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface;
use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface;

class ActivityPersister implements ActivityPersisterInterface
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface
     */
    private $activityHandler;

    /**
     * Constructor
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface $activityHandler
     */
    public function __construct(ActivityHandlerInterface $activityHandler)
    {
        $this->activityHandler = $activityHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $activityId): ?ActivityInterface
    {
        return $this->activityHandler->get($activityId);
    }

    /**
     * {@inheritdoc}
     */
    public function save(string $activityConstant, DateTime $occurredAt, array $payload): int
    {
        $activity = $this->activityHandler->create();
        $activity->setConstant($activityConstant);
        $activity->setOccurredAt($occurredAt);
        $activity->setPayload($payload);

        $this->activityHandler->save($activity);

        return $activity->getActivityId();
    }
}
