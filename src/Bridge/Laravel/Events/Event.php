<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Events;

use EoneoPay\Utils\Collection;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;

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
     * @param mixed[]|null $payload
     * @param mixed[]|null $headers
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
     * Serialize event.
     *
     * @return mixed[]
     */
    abstract public function serialize(): array;

    /**
     * Get event headers.
     *
     * @return mixed[]
     */
    public function getHeaders(): array
    {
        return $this->collection->get('headers')->toArray() ?? [];
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
     * Get event payload.
     *
     * @return mixed[]
     */
    public function getPayload(): array
    {
        return $this->collection->get('payload')->toArray();
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
}
