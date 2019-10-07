<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Events;

use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestRetryEvent;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestRetryEvent
 */
class WebhookRequestRetryEventTest extends TestCase
{
    /**
     * Test get request id.
     *
     * @return void
     */
    public function testGetRequestId(): void
    {
        $event = new WebhookRequestRetryEvent(5);

        self::assertSame(5, $event->getRequestId());
    }
}
