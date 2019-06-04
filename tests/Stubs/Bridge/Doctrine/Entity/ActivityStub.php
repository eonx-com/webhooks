<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity;

use DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Schemas\ActivitySchema;
use EoneoPay\Webhooks\Model\ActivityInterface;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EntityStub;

/**
 * @coversNothing
 */
class ActivityStub implements ActivityInterface
{
    use ActivitySchema;

    /**
     * @var mixed[]
     */
    private $data = [
        'constant' => null,
        'occurredAt' => null,
        'payload' => null
    ];

    /**
     * {@inheritdoc}
     */
    public function getActivityId(): int
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getActivityKey(): string
    {
        return 'activity.key';
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload(): array
    {
        return $this->data['payload'];
    }

    /**
     * {@inheritdoc}
     */
    public function getPrimaryClass(): string
    {
        return EntityStub::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrimaryId(): string
    {
        return '1';
    }

    /**
     * {@inheritdoc}
     */
    public function setActivityKey(string $activityKey): void
    {
        $this->data['constant'] = $activityKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setOccurredAt(DateTime $occurredAt): void
    {
        $this->data['occurredAt'] = $occurredAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setPayload(array $payload): void
    {
        $this->data['payload'] = $payload;
    }
}
