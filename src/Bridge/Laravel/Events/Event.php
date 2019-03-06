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
    private $format;

    /**
     * @var string[]
     */
    private $headers;

    /**
     * @var string
     */
    private $method;

    /**
     * @var mixed[]
     */
    private $payload;

    /**
     * @var int
     */
    private $sequence;

    /**
     * @var string
     */
    private $url;

    /**
     * Event constructor.
     *
     * @param string $url
     * @param int $sequence
     * @param string $format
     * @param null|string $method
     * @param mixed[]|null $payload
     * @param mixed[]|null $headers
     */
    public function __construct(
        string $url,
        int $sequence,
        string $format,
        ?string $method = null,
        ?array $payload = null,
        ?array $headers = null
    ) {
        $this->url = $url;
        $this->sequence = $sequence;
        $this->format = $format;
        $this->method = $method ?? 'POST';
        $this->payload = $payload ?? [];
        $this->headers = $headers ?? [];
    }

    /**
     * @inheritdoc
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
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
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @inheritdoc
     */
    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
