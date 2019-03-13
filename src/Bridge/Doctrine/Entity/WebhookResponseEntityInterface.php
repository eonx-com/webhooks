<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;

interface WebhookResponseEntityInterface
{
    /**
     * Populates the WebhookResponse with data.
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface $webhook
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface $response
     *
     * @return void
     */
    public function populate(WebhookEntityInterface $webhook, ResponseInterface $response): void;
}
