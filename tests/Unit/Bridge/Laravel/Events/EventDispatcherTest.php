<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Events;

use EoneoPay\Webhooks\Bridge\Laravel\Events\EventDispatcher;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Events\EventDispatcher
 */
class EventDispatcherTest extends TestCase
{
    /**
     * Tests activity created
     *
     * @return void
     */
    public function testActivityCreated(): void
    {
        $innerDispatcher = new EventDispatcherStub();
        $dispatcher = new EventDispatcher($innerDispatcher);

        $dispatcher->activityCreated(5);

        /** @var \EoneoPay\Webhooks\Bridge\Laravel\Events\ActivityCreatedEvent[] $dispatched */
        $dispatched = $innerDispatcher->getDispatched();

        static::assertCount(1, $dispatched);
        static::assertSame(5, $dispatched[0]->getActivityId());
    }

    /**
     * Tests webhook request
     *
     * @return void
     */
    public function testWebhookRequest(): void
    {
        $innerDispatcher = new EventDispatcherStub();
        $dispatcher = new EventDispatcher($innerDispatcher);

        $dispatcher->webhookRequest(5);

        /** @var \EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestCreatedEvent[] $dispatched */
        $dispatched = $innerDispatcher->getDispatched();

        static::assertCount(1, $dispatched);
        static::assertSame(5, $dispatched[0]->getRequestId());
    }
}
