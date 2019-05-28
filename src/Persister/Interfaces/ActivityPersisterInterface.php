<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Persister\Interfaces;

use DateTime;
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
     * @param string $activityConstant
     * @param \DateTime $occurredAt
     * @param mixed[] $payload
     *
     * @return int
     */
    public function save(string $activityConstant, DateTime $occurredAt, array $payload): int;
}
