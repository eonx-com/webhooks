<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Events;

use EoneoPay\Utils\Collection;
use EoneoPay\Webhook\Events\Interfaces\EventInterface;

abstract class Event implements EventInterface
{
    /**
     * @var \EoneoPay\Utils\Collection
     */
    protected $collection;

    /**
     * Event constructor.
     *
     * @param null|string $url
     * @param null|string $method
     * @param array|null $payload
     * @param array|null $headers
     */
    public function __construct(
        ?string $url = null,
        ?string $method = null,
        ?array $payload = [],
        ?array $headers = []
    ) {
        $this->collection = new Collection([
            'url' => $url ?? null,
            'method' => $method ?? 'POST',
            'headers' => $headers ?? [],
            'payload' => $payload ?? []
        ]);
    }

    /**
     * Get http url.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->collection->get('url');
    }

    /**
     * Get http method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->collection->get('method');
    }

    /**
     * Get event headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->collection->get('headers')->toArray() ?? [];
    }

    /**
     * Get event payload.
     *
     * @return array
     */
    public function getPayload(): array
    {
        return $this->collection->get('payload')->toArray();
    }

    /**
     * Serialize event.
     *
     * @return array
     */
    abstract public function serialize(): array;
}
