<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Events;

use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

final class Event implements EventInterface, ShouldQueue
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var null|string
     */
    private $payload;

    /**
     * @var string[]
     */
    private $headers;

    /**
     * Event constructor.
     *
     * @param string $url
     * @param null|string $method
     * @param string|null $payload
     * @param mixed[]|null $headers
     */
    public function __construct(
        string $url,
        ?string $method = null,
        ?string $payload = null,
        ?array $headers = null
    ) {
        $this->url = $url;
        $this->method = $method ?? 'POST';
        $this->payload = $payload;
        $this->headers = $headers ?? [];
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @inheritdoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @inheritdoc
     */
    public function getPayload(): ?string
    {
        return $this->payload;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
