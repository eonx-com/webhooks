<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Persister;

use DateTime;
use EoneoPay\Externals\ORM\Interfaces\EntityInterface;
use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface;

/**
 * @coversNothing
 */
class ActivityPersisterStub implements ActivityPersisterInterface
{
    /**
     * @var \EoneoPay\Webhooks\Model\ActivityInterface|null
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
     * @param \EoneoPay\Webhooks\Model\ActivityInterface $activity
     *
     * @return void
     */
    public function setNextActivity(ActivityInterface $activity): void
    {
        $this->nextActivity = $activity;
    }

    /**
     * Sets the next sequence
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
