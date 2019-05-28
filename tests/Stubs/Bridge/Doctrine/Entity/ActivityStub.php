<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity;

use DateTime;
use EoneoPay\Webhooks\Model\ActivityInterface;

class ActivityStub implements ActivityInterface
{
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
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setConstant(string $activityConstant): void
    {
        $this->data['constant'] = $activityConstant;
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
