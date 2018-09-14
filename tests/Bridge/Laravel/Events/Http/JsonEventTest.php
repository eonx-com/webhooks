<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Bridge\Laravel\Events\Http;

use EoneoPay\Webhooks\Bridge\Laravel\Events\Http\JsonEvent;
use Tests\EoneoPay\Webhooks\WebhookTestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Events\Event
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Events\Http\JsonEvent
 */
class JsonEventTest extends WebhookTestCase
{
    /**
     * Test json webhook event payload.
     *
     * @return void
     */
    public function testJsonEventPayload(): void
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ];

        $event = new JsonEvent(
            self::$slackUrl,
            'POST',
            self::$slackPayload,
            []
        );

        self::assertSame(self::$slackUrl, $event->getUrl());
        self::assertSame('POST', $event->getMethod());
        self::assertSame(self::$slackPayload, $event->getPayload());
        self::assertArrayHasKey('headers', $event->serialize());
        self::assertArrayHasKey('body', $event->serialize());
        self::assertSame($headers, $event->getHeaders());
    }
}
