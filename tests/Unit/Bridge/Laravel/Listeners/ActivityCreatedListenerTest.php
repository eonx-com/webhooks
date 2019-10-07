<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Listeners;

use EoneoPay\Webhooks\Bridge\Laravel\Events\ActivityCreatedEvent;
use EoneoPay\Webhooks\Bridge\Laravel\Exceptions\ActivityNotFoundException;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\ActivityCreatedListener;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Persister\ActivityPersisterStub;
use Tests\EoneoPay\Webhooks\Stubs\Webhooks\RequestFactoryStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Listeners\ActivityCreatedListener
 */
class ActivityCreatedListenerTest extends TestCase
{
    /**
     * Tests handle.
     *
     * @return void
     */
    public function testHandle(): void
    {
        $activity = new ActivityStub();

        $persister = new ActivityPersisterStub();
        $persister->setNextActivity($activity);
        $manager = new RequestFactoryStub();

        $listener = new ActivityCreatedListener($persister, $manager);
        $listener->handle(new ActivityCreatedEvent(5));

        self::assertSame([$activity], $manager->getProcessed());
    }

    /**
     * Tests handle fails when Persister returns null.
     *
     * @return void
     */
    public function testHandleFails(): void
    {
        $this->expectException(ActivityNotFoundException::class);
        $this->expectExceptionMessage('No activity was found when querying for activity "5"');

        $persister = new ActivityPersisterStub();
        $manager = new RequestFactoryStub();

        $listener = new ActivityCreatedListener($persister, $manager);
        $listener->handle(new ActivityCreatedEvent(5));
    }
}
