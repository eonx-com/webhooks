<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks\Interfaces;

use EoneoPay\Utils\DateInterval;

interface RetryProcessorInterface
{
    /**
     * Loops through failed requests since date interval and pushes them to queue for re processing.
     *
     * @param \EoneoPay\Utils\DateInterval $dateInterval Interval to go back into to find failed requests
     *
     * @return void
     */
    public function retry(DateInterval $dateInterval): void;
}
