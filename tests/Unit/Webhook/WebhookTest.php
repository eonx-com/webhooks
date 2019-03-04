<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Webhook;

use EoneoPay\Webhooks\Webhook\Webhook;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventCreatorStub;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventStub;
use Tests\EoneoPay\Webhooks\Stubs\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriberStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionRetrieverStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Webhook\Webhook
 */
class WebhookTest extends TestCase
{
    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\EventDispatcherStub
     */
    private $dispatcher;

    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\Event\EventCreatorStub
     */
    private $eventCreator;

    /**
     * @var \Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionRetrieverStub
     */
    private $retriever;

    /**
     * @var \EoneoPay\Webhooks\Webhook\Webhook
     */
    private $webhook;

    /**
     * Test send method
     *
     * @return void
     */
    public function testSend(): void
    {
        $event = new EventStub();
        $this->eventCreator->setEvent($event);

        $this->webhook->send('webhook.event', 2, ['payload' => 'here'], [new SubscriberStub()]);

        static::assertContains($event, $this->dispatcher->getDispatched());
    }

    /**
     * Set up
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->retriever = new SubscriptionRetrieverStub();
        $this->retriever->setToReturn([
            new SubscriptionStub()
        ]);

        $this->dispatcher = new EventDispatcherStub();
        $this->eventCreator = new EventCreatorStub();

        $this->webhook = new Webhook(
            $this->retriever,
            $this->dispatcher,
            $this->eventCreator
        );
    }
}
