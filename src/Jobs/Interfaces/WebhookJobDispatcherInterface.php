<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Jobs\Interfaces;

interface WebhookJobDispatcherInterface
{
    /**
     * Dispatch a job to handler.
     *
     * @param WebhookJobInterface $job
     *
     * @return mixed
     */
    public function dispatch(WebhookJobInterface $job);
}
