<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhook\Bridge\Laravel\Events\Http;

use EoneoPay\Webhook\Bridge\Laravel\Events\Http\XmlEvent;
use Tests\EoneoPay\Webhook\WebhookTestCase;

/**
 * @covers \EoneoPay\Webhook\Bridge\Laravel\Events\Event
 * @covers \EoneoPay\Webhook\Bridge\Laravel\Events\Http\XmlEvent
 */
class XmlEventTest extends WebhookTestCase
{
    /**
     * Test xml event payload.
     *
     * @return void
     */
    public function testXmlEventPayload(): void
    {
        $headers = [
            'Content-Type' => 'application/xml',
            'Accept' => 'application/xml'
        ];

        $rootNode = 'event';

        $event = new XmlEvent(
            self::$httpUrl,
            'POST',
            self::$httpPayload,
            []
        );

        $event->setRootNode($rootNode);

        $serializedData = $event->serialize();

        self::assertSame(self::$httpUrl, $event->getUrl());
        self::assertSame('POST', $event->getMethod());
        self::assertSame(self::$httpPayload, $event->getPayload());
        self::assertArrayHasKey('headers', $serializedData);
        self::assertArrayHasKey('body', $serializedData);
        self::assertContains($rootNode, $serializedData['body']);
        self::assertSame($headers, $event->getHeaders());
    }
}
