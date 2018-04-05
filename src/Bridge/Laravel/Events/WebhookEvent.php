<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Events;

use EoneoPay\Webhook\Events\Interfaces\WebhookEventInterface;
use EoneoPay\Webhook\Payloads\Interfaces\WebhookPayloadInterface;

class WebhookEvent implements WebhookEventInterface
{
    /** @var string URL */
    private $url;

    /** @var null|string Authentication username */
    private $username;

    /** @var null|string Authentication password */
    private $password;

    /** @var null|string Authentication type */
    private $authType;

    /** @var WebhookPayloadInterface The payload */
    private $payload;

    /**
     * WebhookEvent constructor.
     *
     * @param string $url
     * @param WebhookPayloadInterface $payload
     * @param null|string $username
     * @param null|string $password
     * @param null|string $authType
     */
    public function __construct(
        string $url,
        WebhookPayloadInterface $payload,
        ?string $username = null,
        ?string $password = null,
        ?string $authType = null
    ) {
        $this->url = $url;
        $this->payload = $payload;
        $this->username = $username;
        $this->password = $password;
        $this->authType = $authType;
    }

    /**
     * Get URL.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get payload.
     *
     * @return WebhookPayloadInterface
     */
    public function getPayload(): WebhookPayloadInterface
    {
        return $this->payload;
    }

    /**
     * Get authentication username.
     *
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Get authentication password.
     *
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Get authentication type.
     *
     * @return null|string
     */
    public function getAuthType(): ?string
    {
        return $this->authType;
    }
}
