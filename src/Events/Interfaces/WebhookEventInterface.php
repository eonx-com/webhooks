<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Events\Interfaces;

use EoneoPay\Webhooks\Payloads\Interfaces\WebhookPayloadInterface;

interface WebhookEventInterface
{
    /**
     * Get URL.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Get payload.
     *
     * @return WebhookPayloadInterface
     */
    public function getPayload(): WebhookPayloadInterface;

    /**
     * Get authentication username.
     *
     * @return null|string
     */
    public function getUsername(): ?string;

    /**
     * Get authentication password.
     *
     * @return null|string
     */
    public function getPassword(): ?string;

    /**
     * Get authentication type.
     *
     * @return null|string
     */
    public function getAuthType(): ?string;
}
