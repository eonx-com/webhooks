<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Event;

use EoneoPay\Webhooks\Events\Interfaces\EventInterface;

class EventStub implements EventInterface
{
    /**
     * @var string
     */
    private $format;

    /**
     * @var mixed[]
     */
    private $headers;

    /**
     * @var int
     */
    private $sequence;

    /**
     * EventStub constructor.
     *
     * @param string|null $format
     * @param int|null $sequence
     * @param mixed[]|null $headers
     */
    public function __construct(?string $format = null, ?int $sequence = null, ?array $headers = null)
    {
        $this->format = $format ?? 'json';
        $this->headers = $headers ?? [
                'Authorization' => 'Bearer TOKEN'
            ];
        $this->sequence = $sequence ?? 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload(): array
    {
        return ['json' => 'payload'];
    }

    /**
     * {@inheritdoc}
     */
    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(): string
    {
        return 'https://localhost/webhook';
    }
}
