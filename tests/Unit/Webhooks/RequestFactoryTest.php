<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Webhooks;

use EoneoPay\Webhooks\Webhooks\RequestFactory;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\ActivityStub;
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
     * Tests that the processActivity method method resolves subscriptions
     * and saves then dispatches WebhookRequest entities.
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
            $subscription,
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

        self::assertSame($expectedDispatches, $dispatcher->getWebhooksRequested());
        self::assertSame($expectedSave, $webhookPersister->getSaved());
    }
}
