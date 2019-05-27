<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;

interface WebhookResponseInterface
{
    /**
     * Populates the WebhookResponse with data.
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface $request
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface $response
     *
     * @return void
     */
    public function populate(WebhookRequestInterface $request, ResponseInterface $response): void;
}
