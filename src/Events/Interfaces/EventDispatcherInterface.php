<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Events\Interfaces;

interface EventDispatcherInterface
{
    /**
     * Raises a newly saved activity id to be processed by queue workers.
     *
     * @param int $activityId
     *
     * @return void
     */
    public function activityCreated(int $activityId): void;
}
