<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks\Interfaces;

use EoneoPay\Webhooks\Model\ActivityInterface;

interface WebhookManagerInterface
{
    /**
     * Processes an Activity into WebhookRequests for any subscribers of the Activity.
     *
     * @param \EoneoPay\Webhooks\Model\ActivityInterface $activity
     *
     * @return void
     */
    public function processActivity(ActivityInterface $activity): void;
}
