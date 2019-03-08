<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Subscription\Interfaces;

interface SubscriptionInterface
{
    /**
     * Returns the serialization format to be used for serialising the payload.
     *
     * @return string
     */
    public function getSerializationFormat(): string;

    /**
     * The URL to be used for sending the webhook payload.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * The HTTP verb to be used as part of the webhook request.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Any headers to be added to the webhook request.
     *
     * @return string[]
     */
    public function getHeaders(): array;
}
