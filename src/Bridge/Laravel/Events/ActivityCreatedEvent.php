<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Events;

use Illuminate\Contracts\Queue\ShouldQueue;

class ActivityCreatedEvent implements ShouldQueue
{
    /**
     * The activity identifier for the newly created activity.
     *
     * @var int
     */
    private $activityId;

    /**
     * Constructor
     *
     * @param int $activityId
     */
    public function __construct(int $activityId)
    {
        $this->activityId = $activityId;
    }

    /**
     * Returns the activity id.
     *
     * @return int
     */
    public function getActivityId(): int
    {
        return $this->activityId;
    }
}
