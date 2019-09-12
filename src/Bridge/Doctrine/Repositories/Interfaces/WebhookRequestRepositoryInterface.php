<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces;

use DateTime;

interface WebhookRequestRepositoryInterface
{
    /**
     * Get list of webhook requests that have failed since provided date time
     *
     * @param \DateTime $since Datetime since there have been failed webhook requests
     *
     * @return mixed[]
     */
    public function getFailedRequestIds(DateTime $since): iterable;

    /**
     * Get the latest activity request payload for provided primary class with given primary id.
     *
     * @param string $primaryClass Primary class the request is associated with
     * @param string $primaryId Id of the provided primary class
     *
     * @return mixed[]|null
     */
    public function getLatestPayload(string $primaryClass, string $primaryId): ?array;
}
