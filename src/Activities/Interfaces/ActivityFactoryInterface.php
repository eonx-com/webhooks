<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Activities\Interfaces;

/**
 * The ActivityFactory is the entrypoint for where activities are lodged with the
 * system by project code.
 *
 * This factory will create and save an ActivityInterface entity and dispatch an event
 * that allows worker queues to asynchronously handle webhook sending.
 *
 * This service is not responsible for actual webhook processing, see README.md for
 * more detail about how the process proceeds.
 */
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
