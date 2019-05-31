<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Model;

use Psr\Http\Message\ResponseInterface;

interface WebhookResponseInterface
{
    /**
     * Populates the WebhookResponse with data.
     *
     * @param \EoneoPay\Webhooks\Model\WebhookRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param string $truncatedRequest
     *
     * @return void
     */
    public function populateRequest(
        WebhookRequestInterface $request,
        ResponseInterface $response,
        string $truncatedRequest
    ): void;
}
