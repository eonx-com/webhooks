<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces;

use EoneoPay\Webhooks\Models\ActivityInterface;

interface ActivityHandlerInterface
{
    /**
     * Creates a new real instance of ActivityInterface.
     *
     * @return \EoneoPay\Webhooks\Models\ActivityInterface
     */
    public function create(): ActivityInterface;

    /**
     * Returns an activity.
     *
     * @param int $activityId
     *
     * @return \EoneoPay\Webhooks\Models\ActivityInterface|null
     */
    public function get(int $activityId): ?ActivityInterface;

    /**
     * Saves the webhook.
     *
     * @param \EoneoPay\Webhooks\Models\ActivityInterface $activity
     *
     * @return void
     */
    public function save(ActivityInterface $activity): void;
}
