<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Event;

use EoneoPay\Utils\XmlConverter;
use EoneoPay\Webhooks\Bridge\Laravel\Events\EventCreator;
use EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Events\EventCreator
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Events\Event
 */
class EventCreatorTest extends TestCase
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Laravel\Events\EventCreator
     */
    private $creator;

    /**
     * @return mixed[][]
     */
    public function getCreateData(): array
    {
        $payload = [
            'id' => '5',
            'amount' => '10.00'
        ];

        $xmlPayload = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<webhook>
  <sequence>1</sequence>
  <payload>
    <id>5</id>
    <amount>10.00</amount>
  </payload>
</webhook>

XML;

        return [
            'json event' => [$payload, 'json', '{"sequence":1,"payload":{"id":"5","amount":"10.00"}}'],
            'xml event' => [$payload, 'xml', $xmlPayload]
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

        $this->creator = new EventCreator(new XmlConverter());
    }

    /**
     * @dataProvider getCreateData
     *
     * @param mixed[] $payload
     * @param string $format
     * @param string $expectedPayload
     *
     * @return void
     */
    public function testCreate(array $payload, string $format, string $expectedPayload): void
    {
        $subscription = new SubscriptionStub($format);

        $event = $this->creator->create('webhook.event', 1, $payload, $subscription);

        static::assertSame($expectedPayload, $event->getPayload());
    }

    /**
     * Test exception is thrown when unknown serialisation format is used
     *
     * @return void
     */
    public function testCreateUnknownFormat(): void
    {
        $this->expectException(UnknownSerialisationFormatException::class);

        $subscription = new SubscriptionStub('csv');

        $this->creator->create('webhook.event', 1, [], $subscription);
    }
}
