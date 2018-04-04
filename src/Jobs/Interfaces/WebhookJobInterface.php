<?php declare(strict_types=1);

namespace EoneoPay\Webhook\Jobs\Interfaces;

use EoneoPay\External\HttpClient\Interfaces\ResponseInterface;

interface WebhookJobInterface
{
    /**
     * Handle a job.
     *
     * @return \EoneoPay\External\HttpClient\Interfaces\ResponseInterface
     */
    public function handle(): ResponseInterface;
}
