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

        static::assertEquals(99, $result->getSequence());
        static::assertEquals('https://127.0.0.1/webhook', $result->getUrl());
        static::assertEquals(['purple' => 'payload'], $result->getPayload());
        static::assertEquals(['authorization' => 'Bearer ABC123'], $result->getHeaders());
        static::assertEquals('POST', $result->getMethod());
        static::assertEquals('json', $result->getFormat());
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
