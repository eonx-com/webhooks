<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Events\Interfaces;

interface EventInterface
{
    /**
     * Get URL.
     *
     * @return string
     */
    public function getUrl(): string;

    /**
     * Get method.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Get headers.
     *
     * @return mixed[]
     */
    public function getHeaders(): array;

    /**
     * Get payload.
     *
     * @return mixed[]
     */
    public function getPayload(): array;

    /**
     * Serialize event.
     *
     * @return mixed[]
     */
    public function serialize(): array;
}
