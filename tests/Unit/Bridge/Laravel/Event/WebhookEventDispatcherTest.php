<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Event;

use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookEventDispatcher;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventStub;
use Tests\EoneoPay\Webhooks\Stubs\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookEventDispatcher
 */
class WebhookEventDispatcherTest extends TestCase
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookEventDispatcher
     */
    private $dispatcher;

    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\EventDispatcherStub
     */
    private $eventDispatcher;

    /**
     * Test dispatch calls wrapped dispatcher
     *
     * @return void
     */
    public function testDispatch(): void
    {
        $event = new EventStub();

        $this->dispatcher->dispatch($event);

        static::assertContains($event, $this->eventDispatcher->getDispatched());
    }

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->eventDispatcher = new EventDispatcherStub();

        $this->dispatcher = new WebhookEventDispatcher($this->eventDispatcher);
    }
}
