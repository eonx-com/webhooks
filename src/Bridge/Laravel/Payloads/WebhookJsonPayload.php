<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Payloads;

use EoneoPay\Utils\Repository;
use EoneoPay\Webhooks\Payloads\Interfaces\WebhookJsonPayloadInterface;

class WebhookJsonPayload extends Repository implements WebhookJsonPayloadInterface
{
    /**
     * Serialize the payload.
     *
     * @return null|string
     */
    public function serialize(): ?string
    {
        return $this->toJson();
    }
}
