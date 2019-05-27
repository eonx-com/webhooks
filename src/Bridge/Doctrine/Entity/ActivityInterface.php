<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity;

use DateTime;

interface ActivityInterface
{
    /**
     * Returns the persisted activity id.
     *
     * @return int
     */
    public function getActivityId(): int;

    /**
     * Sets the activity constant.
     *
     * @param string $activityConstant
     *
     * @return void
     */
    public function setConstant(string $activityConstant): void;

    /**
     * Sets when the activity occurred.
     *
     * @param \DateTime $occurredAt
     *
     * @return void
     */
    public function setOccurredAt(DateTime $occurredAt): void;

    /**
     * Sets the activity payload.
     *
     * @param mixed[] $payload
     *
     * @return void
     */
    public function setPayload(array $payload): void;
}
