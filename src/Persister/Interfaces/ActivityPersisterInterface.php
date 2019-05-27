<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Persister\Interfaces;

use DateTime;

interface ActivityPersisterInterface
{
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
