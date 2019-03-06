<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Events\Interfaces;

interface EventInterface
{
    /**
     * Returns the request format
     *
     * @return string
     */
    public function getFormat(): string;

    /**
     * Get headers.
     *
     * @return string[]
     */
    public function getHeaders(): array;

    /**
     * Get method.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Get payload.
     *
     * @return mixed[]
     */
    public function getPayload(): array;

    /**
     * Webhook sequence number
     *
     * @return int
     */
    public function getSequence(): int;

    /**
     * Get URL.
     *
     * @return string
     */
    public function getUrl(): string;
}
