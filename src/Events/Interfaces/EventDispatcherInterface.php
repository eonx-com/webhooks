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

    /**
     * A new webhook request raised to be processed by queue workers.
     *
     * @param int $requestId
     *
     * @return void
     */
    public function webhookRequest(int $requestId): void;
}
