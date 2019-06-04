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
    public function dispatchActivityCreated(int $activityId): void;

    /**
     * Once an activity has had its subscribers resolved, a webhook request is
     * saved for each subscriber. This event is raised for each request so
     * they can be processed by queue workers.
     *
     * @param int $requestId
     *
     * @return void
     */
    public function dispatchRequestCreated(int $requestId): void;
}
