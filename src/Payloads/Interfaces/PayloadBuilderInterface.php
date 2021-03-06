<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Payloads\Interfaces;

use EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface;

interface PayloadBuilderInterface
{
    /**
     * Returns the payload for the activity.
     *
     * @param \EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface $activityData
     *
     * @return mixed[]
     */
    public function buildPayload(ActivityDataInterface $activityData): array;

    /**
     * Indicates if this payload builder supports building a payload for
     * the specific ActivityDataInterface.
     *
     * @param \EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface $data
     *
     * @return bool
     */
    public function supports(ActivityDataInterface $data): bool;
}
