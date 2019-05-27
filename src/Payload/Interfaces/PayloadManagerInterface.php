<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Payload\Interfaces;

use EoneoPay\Webhooks\Activity\Interfaces\ActivityDataInterface;

interface PayloadManagerInterface
{
    /**
     * Builds a payload for a given activity data object.
     *
     * @param \EoneoPay\Webhooks\Activity\Interfaces\ActivityDataInterface $activityData
     *
     * @return mixed[]
     */
    public function buildPayload(ActivityDataInterface $activityData): array;
}
