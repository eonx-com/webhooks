<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Events\Http;

use EoneoPay\Utils\Arr;
use EoneoPay\Webhook\Bridge\Laravel\Events\Event;
use EoneoPay\Webhook\Events\Interfaces\Http\JsonEventInterface;

class JsonEvent extends Event implements JsonEventInterface
{
    /**
     * JsonEvent constructor.
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
        // merge json headers
        $headers = $this->mergeHeaders($headers);

        // call parent constructor
        parent::__construct($url, $method, $payload, $headers);
    }

    /**
     * Serialize json event.
     *
     * @return array
     */
    public function serialize(): array
    {
        return [
            'headers' => $this->collection->get('headers')->toArray() ?? [],
            'body' => $this->collection->get('payload')->toJson()
        ];
    }

    /**
     * Merge json headers.
     *
     * @param array|null $headers
     *
     * @return array
     */
    private function mergeHeaders(?array $headers = []): array
    {
        $headers = $headers ?? [];

        $arr = new Arr();

        return $arr->merge($headers, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
    }
}
