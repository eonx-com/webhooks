<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Events\Interfaces;

interface EventInterface
{
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
     * @return string|null
     */
    public function getPayload(): ?string;

    /**
     * Get URL.
     *
     * @return string
     */
    public function getUrl(): string;
}
