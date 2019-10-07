<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Events;

use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestCreatedEvent;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestCreatedEvent
 */
class WebhookRequestCreatedEventTest extends TestCase
{
    /**
     * Test get activity id.
     *
     * @return void
     */
    public function testGetRequestId(): void
    {
        $event = new WebhookRequestCreatedEvent(5);

        self::assertSame(5, $event->getRequestId());
    }
}
