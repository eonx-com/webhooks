<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Persister\Interfaces;

use DateTime;
use EoneoPay\Externals\ORM\Interfaces\EntityInterface;
use EoneoPay\Webhooks\Model\ActivityInterface;

interface ActivityPersisterInterface
{
    /**
     * Returns an activity.
     *
     * @param int $activityId
     *
     * @return \EoneoPay\Webhooks\Model\ActivityInterface|null
     */
    public function get(int $activityId): ?ActivityInterface;

    /**
     * Saves the ActivityData object with the generated payload.
     *
     * @param string $activityKey
     * @param \EoneoPay\Externals\ORM\Interfaces\EntityInterface $primaryEntity
     * @param \DateTime $occurredAt
     * @param mixed[] $payload
     *
     * @return int
     */
    public function save(
        string $activityKey,
        EntityInterface $primaryEntity,
        DateTime $occurredAt,
        array $payload
    ): int;
}
