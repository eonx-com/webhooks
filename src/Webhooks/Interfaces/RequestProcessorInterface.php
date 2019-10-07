<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks\Interfaces;

use EoneoPay\Webhooks\Models\WebhookRequestInterface;

interface RequestProcessorInterface
{
    /**
     * Processes a request and emits the webhook request, saving the response.
     *
     * @param \EoneoPay\Webhooks\Models\WebhookRequestInterface $webhookRequest
     *
     * @return void
     */
    public function process(WebhookRequestInterface $webhookRequest): void;
}
