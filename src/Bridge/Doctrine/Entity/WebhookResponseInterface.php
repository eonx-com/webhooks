<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;

interface WebhookResponseInterface
{
    /**
     * Populates the WebhookResponse with data.
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface $webhookRequest
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface $response
     *
     * @return void
     */
    public function populate(WebhookRequestInterface $webhookRequest, ResponseInterface $response): void;
}
