<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks\Interfaces;

use EoneoPay\Webhooks\Models\ActivityInterface;

interface RequestFactoryInterface
{
    /**
     * Processes an Activity into WebhookRequests for any subscribers of the Activity.
     *
     * @param \EoneoPay\Webhooks\Models\ActivityInterface $activity
     *
     * @return void
     */
    public function processActivity(ActivityInterface $activity): void;
}
