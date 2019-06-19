<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces;

use DateTime;
use Iterator;

interface WebhookRequestRepositoryInterface
{
    /**
     * Get list of webhook requests that have failed since provided date time
     *
     * @param \DateTime $since Datetime since there have been failed webhook requests
     *
     * @return \Iterator
     */
    public function getFailedRequests(DateTime $since): Iterator;
}
