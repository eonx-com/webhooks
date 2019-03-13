<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface;

interface WebhookHandlerInterface
{
    /**
     * Creates a new real instance of WebhookEntityInterface
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface
     */
    public function createNewWebhook(): WebhookEntityInterface;

    /**
     * Returns a webhook given its sequence number
     *
     * @param int $sequence
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface
     */
    public function getWebhook(int $sequence): WebhookEntityInterface;

    /**
     * Saves the webhook
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface $webhook
     *
     * @return void
     */
    public function save(WebhookEntityInterface $webhook): void;
}
