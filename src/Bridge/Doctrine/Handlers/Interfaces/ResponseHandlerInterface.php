<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces;

use EoneoPay\Webhooks\Models\WebhookResponseInterface;

interface ResponseHandlerInterface
{
    /**
     * Creates a new real instance of WebhookResponseEntityInterface.
     *
     * @return \EoneoPay\Webhooks\Models\WebhookResponseInterface
     */
    public function createNewWebhookResponse(): WebhookResponseInterface;

    /**
     * Saves the webhook.
     *
     * @param \EoneoPay\Webhooks\Models\WebhookResponseInterface $response
     *
     * @return void
     */
    public function save(WebhookResponseInterface $response): void;
}
