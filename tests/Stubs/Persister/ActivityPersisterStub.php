<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Persister;

use DateTime;
use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface;

class ActivityPersisterStub implements ActivityPersisterInterface
{
    /**
     * @var int
     */
    private $nextSequence = 1;

    /**
     * @var mixed[]
     */
    private $saved = [];

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
    public function get(int $activityId): ?ActivityInterface
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function save(string $activityConstant, DateTime $occurredAt, array $payload): int
    {
        $this->saved[] = \compact('activityConstant', 'occurredAt', 'payload');

        return $this->nextSequence;
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
