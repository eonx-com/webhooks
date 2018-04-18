<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Jobs\Interfaces;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;

interface WebhookJobInterface
{
    /**
     * Handle a job.
     *
     * @return \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface
     */
    public function handle(): ?ResponseInterface;
}
