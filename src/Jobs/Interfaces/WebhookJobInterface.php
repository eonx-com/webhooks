<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Jobs\Interfaces;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;

interface WebhookJobInterface
{
    /**
     * Handle webhook event job.
     *
     * @return \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface|null
     *
     * @throws \EoneoPay\Externals\HttpClient\Exceptions\InvalidApiResponseException
     */
    public function handle(): ?ResponseInterface;
}
