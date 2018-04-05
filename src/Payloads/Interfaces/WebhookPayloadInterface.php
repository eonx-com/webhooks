<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Payloads\Interfaces;

interface WebhookPayloadInterface
{
    /**
     * Serialize the payload.
     *
     * @return mixed
     */
    public function serialize();
}
