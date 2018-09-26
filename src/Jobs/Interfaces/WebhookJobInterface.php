<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Jobs\Interfaces;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;

interface WebhookJobInterface
{
    /**
     * Handle webhook event job.
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface $httpClient
     *
     * @return \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface|null
     */
    public function handle(ClientInterface $httpClient): ?ResponseInterface;
}
