<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Persisters\Interfaces;

use DateTime;
use EoneoPay\Externals\ORM\Interfaces\EntityInterface;
use EoneoPay\Webhooks\Models\ActivityInterface;

interface ActivityPersisterInterface
{
    /**
     * Add sequence number to existing activity payload.
     *
     * @param int $activityId Activity id.
     *
     * @return void
     */
    public function addSequenceToPayload(int $activityId): void;

    /**
     * Returns an activity.
     *
     * @param int $activityId
     *
     * @return \EoneoPay\Webhooks\Models\ActivityInterface|null
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
