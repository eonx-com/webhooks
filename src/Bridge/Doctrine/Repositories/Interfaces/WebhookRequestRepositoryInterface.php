<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces;

use DateTime;
use EoneoPay\Webhooks\Models\ActivityInterface;

interface WebhookRequestRepositoryInterface extends FillableRepositoryInterface
{
    /**
     * Get list of webhook requests that have failed since provided date time.
     *
     * @param \DateTime $since Datetime since there have been failed webhook requests
     *
     * @return mixed[]
     */
    public function getFailedRequestIds(DateTime $since): iterable;

    /**
     * Get the latest activity from webhook request for provided primary class with given
     * primary id.
     *
     * @param string $primaryClass Primary class the request is associated with
     * @param string $primaryId Id of the provided primary class
     *
     * @return \EoneoPay\Webhooks\Models\ActivityInterface|null
     */
    public function getLatestActivity(string $primaryClass, string $primaryId): ?ActivityInterface;
}
