<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Bridge\Laravel\Events;

use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookEventDispatcher;
use Illuminate\Events\Dispatcher as IlluminateDispatcher;
use Tests\EoneoPay\Webhooks\WebhookTestCase;

class WebhookEventDispatcherTest extends WebhookTestCase
{
    /**
     * Webhook event dispatcher
     *
     * @var \EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookEventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Setup.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        // init dispatcher
        $illuminateDispatcher = new IlluminateDispatcher();
        // init Webhook event dispatcher
        $this->eventDispatcher = new WebhookEventDispatcher($illuminateDispatcher);
    }

    /**
     * Test webhook event dispatch.
     *
     * @return void
     */
    public function testDispatch(): void
    {
        self::assertInternalType('array', $this->eventDispatcher->dispatch('test-event', []));
        self::assertNull($this->eventDispatcher->dispatch('test-event', [], true));
    }
}
