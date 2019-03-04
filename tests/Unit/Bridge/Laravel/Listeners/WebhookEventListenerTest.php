<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Listeners;

use EoneoPay\Webhooks\Bridge\Laravel\Events\Event;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener;
use Tests\EoneoPay\Webhooks\Stubs\ClientStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Events\Event
 */
class WebhookEventListenerTest extends TestCase
{
    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\ClientStub
     */
    private $client;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener
     */
    private $listener;

    /**
     * Test handle method
     *
     * @return void
     */
    public function testHandle(): void
    {
        $event = new Event(
            'https://localhost/webhook',
            'POST',
            '{"json":"payload"}',
            [
                'Authorization' => 'Bearer TOKEN',
                'Content-Type' => 'application/json'
            ]
        );

        $this->listener->handle($event);

        static::assertEquals([
            [
                'method' => 'POST',
                'uri' => 'https://localhost/webhook',
                'options' => [
                    'body' => '{"json":"payload"}',
                    'headers' => [
                        'Authorization' => 'Bearer TOKEN',
                        'Content-Type' => 'application/json'
                    ]
                ]
            ]
        ], $this->client->getRequests());
    }

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new ClientStub();

        $this->listener = new WebhookEventListener($this->client);
    }
}
