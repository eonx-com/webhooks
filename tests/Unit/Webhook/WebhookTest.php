<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Webhook;

use EoneoPay\Webhooks\Webhook\Webhook;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventCreatorStub;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventStub;
use Tests\EoneoPay\Webhooks\Stubs\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriberStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionRetrieverStub;
use Tests\EoneoPay\Webhooks\Stubs\Webhooks\WebhookDataStub;
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
        $event1 = new EventStub();
        $this->eventCreator->addEvent($event1);
        $event2 = new EventStub();
        $this->eventCreator->addEvent($event2);

        $data = new WebhookDataStub('event', ['payload' => 'here'], [
            new SubscriberStub(),
            new SubscriberStub()
        ]);

        $this->webhook->send($data);

        static::assertContains($event1, $this->dispatcher->getDispatched());
        static::assertContains($event2, $this->dispatcher->getDispatched());
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
        $this->dispatcher = new EventDispatcherStub();
        $this->eventCreator = new EventCreatorStub();

        $this->webhook = new Webhook(
            $this->retriever,
            $this->dispatcher,
            $this->eventCreator
        );
    }
}
