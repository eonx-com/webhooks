<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Externals\ORM\Entity;
use EoneoPay\Externals\ORM\Interfaces\EntityInterface;
use EoneoPay\Utils\Interfaces\UtcDateTimeInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Schemas\ActivitySchema;
use EoneoPay\Webhooks\Model\ActivityInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="event_activities")
 */
class Activity extends Entity implements ActivityInterface
{
    use ActivitySchema;

    // @codeCoverageIgnoreStart

    /**
     * The Activity entity in this package is not intended to be created
     * manually. Use the ActivityPersister to create new Activity objects.
     *
     * @noinspection MagicMethodsValidityInspection PhpMissingParentConstructorInspection
     */
    private function __construct()
    {
    }
    // @codeCoverageIgnoreEnd

    /**
     * {@inheritdoc}
     */
    public function getActivityId(): int
    {
        // Casting to a string: bigint is hydrated as a string.

        return (int)$this->activityId;
    }

    /**
     * {@inheritdoc}
     */
    public function getActivityKey(): string
    {
        return $this->activityKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getOccurredAt(): ?DateTime
    {
        return $this->occurredAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrimaryClass(): string
    {
        return $this->primaryClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrimaryId(): string
    {
        return $this->primaryId;
    }

    /**
     * {@inheritdoc}
     */
    public function setActivityKey(string $activityKey): void
    {
        $this->activityKey = $activityKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setOccurredAt(DateTime $occurredAt): void
    {
        $this->occurredAt = $occurredAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * {@inheritdoc}
     */
    public function setPrimaryEntity(EntityInterface $primaryObject): void
    {
        $this->primaryClass = \get_class($primaryObject);
        $this->primaryId = (string)$primaryObject->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'activity_key' => $this->getActivityKey(),
            'id' => $this->getActivityId(),
            'occurred_at' => $this->getOccurredAt() !== null
                ? $this->getOccurredAt()->format(UtcDateTimeInterface::FORMAT_ZULU)
                : null,
            'payload' => $this->getPayload()
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getIdProperty(): string
    {
        return 'activityId';
    }
}
