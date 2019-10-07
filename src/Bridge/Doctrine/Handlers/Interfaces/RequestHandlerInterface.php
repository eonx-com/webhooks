<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces;

use EoneoPay\Webhooks\Models\WebhookRequestInterface;

interface RequestHandlerInterface
{
    /**
     * Creates a new real instance of WebhookRequestInterface.
     *
     * @return \EoneoPay\Webhooks\Models\WebhookRequestInterface
     */
    public function create(): WebhookRequestInterface;

    /**
     * Returns a webhook given its sequence number.
     *
     * @param int $sequence
     *
     * @return \EoneoPay\Webhooks\Models\WebhookRequestInterface
     */
    public function getBySequence(int $sequence): WebhookRequestInterface;

    /**
     * Saves the webhook.
     *
     * @param \EoneoPay\Webhooks\Models\WebhookRequestInterface $webhook
     *
     * @return void
     */
    public function save(WebhookRequestInterface $webhook): void;
}
