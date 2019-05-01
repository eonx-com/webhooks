<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Events;

use EoneoPay\Webhooks\Events\LoggerAwareEventDispatcher;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventStub;
use Tests\EoneoPay\Webhooks\Stubs\Event\WebhookEventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\LoggerStub;
use Tests\EoneoPay\Webhooks\TestCase;

class LoggerAwareEventDispatcherTest extends TestCase
{
    /**
     * @var \EoneoPay\Webhooks\Events\LoggerAwareEventDispatcher
     */
    private $dispatcher;

    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\Event\WebhookEventDispatcherStub
     */
    private $innerDispatcher;

    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\LoggerStub
     */
    private $logger;

    /**
     * Tests dispatch
     *
     * @return void
     */
    public function testDispatch(): void
    {
        $event = new EventStub();

        $this->dispatcher->dispatch($event);

        static::assertContains($event, $this->innerDispatcher->getDispatched());
        static::assertCount(1, $this->logger->getLogs());
        static::assertSame([
            'message' => 'Dispatching Webhook',
            'context' => [
                'format' => 'json',
                'headers' => [
                    'Authorization' => 'REDACTED'
                ],
                'method' => 'POST',
                'payload' => ['json' => 'payload'],
                'sequence' => 1,
                'url' => 'https://localhost/webhook'
            ]
        ], $this->logger->getLogs()[0]);
    }

    /**
     * Tests dispatch
     *
     * @return void
     */
    public function testDispatchLowercaseAuthorize(): void
    {
        $event = new EventStub(null, null, [
            'authorization' => 'hunter2'
        ]);

        $this->dispatcher->dispatch($event);

        static::assertContains($event, $this->innerDispatcher->getDispatched());
        static::assertCount(1, $this->logger->getLogs());
        static::assertSame([
            'message' => 'Dispatching Webhook',
            'context' => [
                'format' => 'json',
                'headers' => [
                    'authorization' => 'REDACTED'
                ],
                'method' => 'POST',
                'payload' => ['json' => 'payload'],
                'sequence' => 1,
                'url' => 'https://localhost/webhook'
            ]
        ], $this->logger->getLogs()[0]);
    }

    /**
     * set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->innerDispatcher = new WebhookEventDispatcherStub();
        $this->logger = new LoggerStub();

        $this->dispatcher = new LoggerAwareEventDispatcher(
            $this->innerDispatcher,
            $this->logger
        );
    }
}
