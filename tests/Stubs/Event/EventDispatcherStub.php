<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Event;

use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;

/**
 * @coversNothing
 */
class EventDispatcherStub implements EventDispatcherInterface
{
    /**
     * @var int[]
     */
    private $activityCreated = [];

    /**
     * {@inheritdoc}
     */
    public function activityCreated(int $activityId): void
    {
        $this->activityCreated[] = $activityId;
    }

    /**
     * Returns raised activities
     *
     * @return int[]
     */
    public function getActivityCreated(): array
    {
        return $this->activityCreated;
    }
}
