<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Listeners;

use EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener;
use Tests\EoneoPay\Webhooks\Stubs\Client\ClientStub;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Events\Event
 */
class WebhookEventListenerTest extends TestCase
{
    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\Client\ClientStub
     */
    private $client;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener
     */
    private $listener;

    /**
     * Tests event listener delgates
     *
     * @return void
     */
    public function testEventListener(): void
    {
        $event = new EventStub();

        $this->listener->handle($event);

        static::assertContains($event, $this->client->getSent());
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
