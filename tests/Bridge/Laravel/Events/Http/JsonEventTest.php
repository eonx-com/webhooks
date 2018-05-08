<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhook\Bridge\Laravel\Events\Http;

use EoneoPay\Webhook\Bridge\Laravel\Events\Http\JsonEvent;
use Tests\EoneoPay\Webhook\WebhookTestCase;

/**
 * @covers \EoneoPay\Webhook\Bridge\Laravel\Events\Event
 * @covers \EoneoPay\Webhook\Bridge\Laravel\Events\Http\JsonEvent
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
