<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Models;

use DateTime;
use EoneoPay\Externals\ORM\Interfaces\EntityInterface;

interface ActivityInterface
{
    /**
     * Returns the persisted activity id.
     *
     * @return int
     */
    public function getActivityId(): int;

    /**
     * Returns the activity key for this activity.
     *
     * @return string
     */
    public function getActivityKey(): string;

    /**
     * Returns occurred at date for the activity.
     *
     * @return \DateTime|null
     */
    public function getOccurredAt(): ?DateTime;

    /**
     * Returns the activity payload.
     *
     * @return mixed[]
     */
    public function getPayload(): array;

    /**
     * Returns the class of the Primary entity.
     *
     * @return string
     */
    public function getPrimaryClass(): string;

    /**
     * Returns the identifier of the Primary entity.
     *
     * @return string
     */
    public function getPrimaryId(): string;

    /**
     * Sets the activity key.
     *
     * @param string $activityKey
     *
     * @return void
     */
    public function setActivityKey(string $activityKey): void;

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

    /**
     * Sets the primary entity that caused this activity.
     *
     * @param \EoneoPay\Externals\ORM\Interfaces\EntityInterface $primaryObject
     *
     * @return void
     */
    public function setPrimaryEntity(EntityInterface $primaryObject): void;
}
