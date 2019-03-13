<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseEntityInterface;

interface ResponseHandlerInterface
{
    /**
     * Creates a new real instance of WebhookEntityInterface
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseEntityInterface
     */
    public function createNewWebhookResponse(): WebhookResponseEntityInterface;

    /**
     * Saves the webhook
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseEntityInterface $response
     *
     * @return void
     */
    public function save(WebhookResponseEntityInterface $response): void;
}
