<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Events;

use EoneoPay\Webhooks\Bridge\Laravel\Events\ActivityCreatedEvent;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Events\ActivityCreatedEvent
 */
class ActivityCreatedEventTest extends TestCase
{
    /**
     * Test get activity id.
     *
     * @return void
     */
    public function testGetActivityId(): void
    {
        $event = new ActivityCreatedEvent(5);

        self::assertSame(5, $event->getActivityId());
    }
}
