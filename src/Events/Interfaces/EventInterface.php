<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Events\Interfaces;

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
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Get payload.
     *
     * @return array
     */
    public function getPayload(): array;

    /**
     * Serialize event.
     *
     * @return array
     */
    public function serialize(): array;
}
