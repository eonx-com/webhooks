<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Webhooks;

use EoneoPay\Webhooks\Webhooks\RequestFactory;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\Persister\WebhookPersisterStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionResolverStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Webhooks\RequestFactory
 */
class RequestFactoryTest extends TestCase
{
    /**
     * Tests processActivity method
     *
     * @return void
     */
    public function testProcess(): void
    {
        $activity = new ActivityStub();
        $subscription = new SubscriptionStub();

        $expectedDispatches = [15];
        $expectedSave = [\compact('activity', 'subscription')];

        $subscriptionResolver = new SubscriptionResolverStub();

        $subscriptionResolver->setSubscriptions([
            $subscription
        ]);
        $webhookPersister = new WebhookPersisterStub();
        $webhookPersister->setNextSequence(15);

        $dispatcher = new EventDispatcherStub();

        $factory = new RequestFactory(
            $dispatcher,
            $subscriptionResolver,
            $webhookPersister
        );

        $factory->processActivity($activity);

        static::assertSame($expectedDispatches, $dispatcher->getWebhooksRequested());
        static::assertSame($expectedSave, $webhookPersister->getSaved());
    }
}
