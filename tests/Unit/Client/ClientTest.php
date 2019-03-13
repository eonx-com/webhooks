<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Client;

use EoneoPay\Utils\XmlConverter;
use EoneoPay\Webhooks\Client\Client;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventStub;
use Tests\EoneoPay\Webhooks\Stubs\HttpClientStub;
use Tests\EoneoPay\Webhooks\Stubs\Persister\WebhookPersisterStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Client\Client
 */
class ClientTest extends TestCase
{
    /**
     * @var \EoneoPay\Webhooks\Client\Client
     */
    private $client;

    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\HttpClientStub
     */
    private $httpClient;

    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\Persister\WebhookPersisterStub
     */
    private $persister;

    /**
     * Returns data for testSend
     *
     * @return mixed[][]
     */
    public function getSendData(): array
    {
        $xmlPayload = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<webhook>
  <json>payload</json>
  <_sequence>22</_sequence>
</webhook>

XML;

        return [
            'json' => [
                new EventStub(),
                '{"json":"payload","_sequence":1}',
                'application/json'
            ],
            'xml' => [
                new EventStub('xml', 22),
                $xmlPayload,
                'application/xml'
            ]
        ];
    }

    /**
     * Tests send method
     *
     * @dataProvider getSendData
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     * @param string $expectedPayload
     * @param string $expectedContentType
     *
     * @return void
     */
    public function testSend(
        EventInterface $event,
        string $expectedPayload,
        string $expectedContentType
    ): void {
        $this->client->send($event);

        $requests = $this->httpClient->getRequests();
        static::assertCount(1, $requests);

        $request = \reset($requests);

        static::assertEquals('POST', $request['method']);
        static::assertEquals('https://localhost/webhook', $request['uri']);
        static::assertEquals($expectedPayload, $request['options']['body']);
        static::assertEquals([
            'Authorization' => 'Bearer TOKEN',
            'Content-Type' => $expectedContentType
        ], $request['options']['headers']);

        $responses = $this->persister->getUpdates();
        static::assertCount(1, $responses);

        $response = \reset($responses);

        static::assertEquals(204, $response['response']->getStatusCode());
        static::assertEquals($event->getSequence(), $response['sequence']);
    }

    /**
     * Tests send bad format
     *
     * @return void
     */
    public function testSendBadFormat(): void
    {
        $this->expectException(UnknownSerialisationFormatException::class);

        $event = new EventStub('csv');

        $this->client->send($event);
    }

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = new HttpClientStub();
        $this->persister = new WebhookPersisterStub();

        $this->client = new Client(
            $this->httpClient,
            new XmlConverter(),
            $this->persister
        );
    }
}
