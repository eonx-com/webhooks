<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Persisters;

use DateTime;
use EoneoPay\Externals\ORM\Interfaces\EntityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface;
use EoneoPay\Webhooks\Models\ActivityInterface;
use EoneoPay\Webhooks\Persisters\Interfaces\ActivityPersisterInterface;

final class ActivityPersister implements ActivityPersisterInterface
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface
     */
    private $activityHandler;

    /**
     * Constructor.
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
    public function save(string $activityKey, EntityInterface $primaryEntity, DateTime $occurredAt, array $payload): int
    {
        $activity = $this->activityHandler->create();
        $activity->setActivityKey($activityKey);
        $activity->setOccurredAt($occurredAt);
        $activity->setPayload($payload);
        $activity->setPrimaryEntity($primaryEntity);

        $this->activityHandler->save($activity);

        return $activity->getActivityId();
    }
}
