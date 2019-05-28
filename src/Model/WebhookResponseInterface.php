<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Model;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;

interface WebhookResponseInterface
{
    /**
     * Populates the WebhookResponse with data.
     *
     * @param \EoneoPay\Webhooks\Model\WebhookRequestInterface $request
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface $response
     *
     * @return void
     */
    public function populate(WebhookRequestInterface $request, ResponseInterface $response): void;
}
