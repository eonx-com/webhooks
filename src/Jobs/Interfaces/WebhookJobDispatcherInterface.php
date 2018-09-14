<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Jobs\Interfaces;

interface WebhookJobDispatcherInterface
{
    /**
     * Dispatch a job to handler.
     *
     * @param \EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobInterface $job
     *
     * @return mixed
     */
    public function dispatch(WebhookJobInterface $job);
}
