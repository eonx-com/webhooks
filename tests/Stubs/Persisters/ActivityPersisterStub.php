<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Persisters;

use DateTime;
use EoneoPay\Externals\ORM\Interfaces\EntityInterface;
use EoneoPay\Webhooks\Models\ActivityInterface;
use EoneoPay\Webhooks\Persisters\Interfaces\ActivityPersisterInterface;

/**
 * @coversNothing
 */
class ActivityPersisterStub implements ActivityPersisterInterface
{
    /**
     * Sequence added to the activity payload.
     *
     * @var int|null
     */
    private $addedSequence;

    /**
     * @var \EoneoPay\Webhooks\Models\ActivityInterface|null
     */
    private $nextActivity;

    /**
     * @var int
     */
    private $nextSequence = 1;

    /**
     * @var mixed[]
     */
    private $saved = [];

    /**
     * {@inheritdoc}
     */
    public function get(int $activityId): ?ActivityInterface
    {
        return $this->nextActivity;
    }

    /**
     * Get added sequence the payload.
     *
     * @return int|null
     */
    public function getAddedSequence(): ?int
    {
        return $this->addedSequence;
    }

    /**
     * @return mixed[]
     */
    public function getSaved(): array
    {
        return $this->saved;
    }

    /**
     * {@inheritdoc}
     */
    public function save(string $activityKey, EntityInterface $entity, DateTime $occurredAt, array $payload): int
    {
        $this->saved[] = \compact('activityKey', 'entity', 'occurredAt', 'payload');

        return $this->nextSequence;
    }

    /**
     * Sets next activity returned by get.
     *
     * @param \EoneoPay\Webhooks\Models\ActivityInterface $activity
     *
     * @return void
     */
    public function setNextActivity(ActivityInterface $activity): void
    {
        $this->nextActivity = $activity;
    }

    /**
     * Sets the next sequence.
     *
     * @param int $seq
     *
     * @return void
     */
    public function setNextSequence(int $seq): void
    {
        $this->nextSequence = $seq;
    }
}
