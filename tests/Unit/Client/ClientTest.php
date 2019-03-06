<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Client;

use EoneoPay\Utils\XmlConverter;
use EoneoPay\Webhooks\Client\Client;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventStub;
use Tests\EoneoPay\Webhooks\Stubs\HttpClientStub;
use Tests\EoneoPay\Webhooks\TestCase;

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
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = new HttpClientStub();

        $this->client = new Client($this->httpClient, new XmlConverter());
    }

//    /**
//     * @return mixed[][]
//     */
//    public function getCreateData(): array
//    {
//        $payload = [
//            'id' => '5',
//            'amount' => '10.00'
//        ];
//
//        $xmlPayload = <<<XML
//<?xml version="1.0" encoding="UTF-8" ? >
//<webhook>
//  <sequence>1</sequence>
//  <payload>
//    <id>5</id>
//    <amount>10.00</amount>
//  </payload>
//</webhook>
//
//XML;
//
//        return [
//            'json event' => [$payload, 'json', '{"sequence":1,"payload":{"id":"5","amount":"10.00"}}'],
//            'xml event' => [$payload, 'xml', $xmlPayload]
//        ];
//    }
//    /**
//     * Test handle method
//     *
//     * @return void
//     */
//    public function testHandle(): void
//    {
//        $event = new Event(
//            'https://localhost/webhook',
//            'POST',
//            '{"json":"payload"}',
//            [
//                'Authorization' => 'Bearer TOKEN',
//                'Content-Type' => 'application/json'
//            ]
//        );
//
//        $this->listener->handle($event);
//
//        static::assertEquals([
//            [
//                'method' => 'POST',
//                'uri' => 'https://localhost/webhook',
//                'options' => [
//                    'body' => '{"json":"payload"}',
//                    'headers' => [
//                        'Authorization' => 'Bearer TOKEN',
//                        'Content-Type' => 'application/json'
//                    ]
//                ]
//            ]
//        ], $this->client->getRequests());
//    }
}
