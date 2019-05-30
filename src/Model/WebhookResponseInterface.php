<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Model;

interface WebhookResponseInterface
{
    /**
     * Populates the WebhookResponse with data.
     *
     * @param \EoneoPay\Webhooks\Model\WebhookRequestInterface $request
     * @param string $response
     *
     * @return void
     */
    public function populate(WebhookRequestInterface $request, string $response): void;
}
