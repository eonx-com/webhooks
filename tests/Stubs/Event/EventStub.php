<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Event;

use EoneoPay\Webhooks\Events\Interfaces\EventInterface;

class EventStub implements EventInterface
{
    /**
     * @inheritdoc
     */
    public function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer TOKEN',
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @inheritdoc
     */
    public function getPayload(): ?string
    {
        return '{"json":"payload"}';
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        return 'https://localhost/webhook';
    }
}
