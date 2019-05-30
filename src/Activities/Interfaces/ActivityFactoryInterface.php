<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Activities\Interfaces;

interface ActivityFactoryInterface
{
    /**
     * The entry point for creating activities.
     *
     * @param \EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface $activityData
     *
     * @return void
     */
    public function send(ActivityDataInterface $activityData): void;
}
