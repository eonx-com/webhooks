<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Event;

use EoneoPay\Webhooks\Bridge\Laravel\Events\EventCreator;
use Tests\EoneoPay\Webhooks\Stubs\Persister\WebhookPersisterStub;
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
     * @var \Tests\EoneoPay\Webhooks\Stubs\Persister\WebhookPersisterStub
     */
    private $persister;

    /**
     * Test that create works
     *
     * @return void
     */
    public function testCreate(): void
    {
        $subscription = new SubscriptionStub('json');

        $this->persister->setNextSequence(99);

        $result = $this->creator->create('event', ['purple' => 'payload'], $subscription);

        static::assertSame(99, $result->getSequence());
        static::assertSame('https://127.0.0.1/webhook', $result->getUrl());
        static::assertSame(['purple' => 'payload'], $result->getPayload());
        static::assertSame(['authorization' => 'Bearer ABC123'], $result->getHeaders());
        static::assertSame('POST', $result->getMethod());
        static::assertSame('json', $result->getFormat());
        static::assertSame('event', $result->getEvent());
    }

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->persister = new WebhookPersisterStub();

        $this->creator = new EventCreator($this->persister);
    }
}
