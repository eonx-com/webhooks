<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Activities;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Activities\ActivityFactory;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Payload\Interfaces\PayloadManagerInterface;
use EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface;
use Tests\EoneoPay\Webhooks\Stubs\Activity\ActivityDataStub;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\Payload\PayloadManagerStub;
use Tests\EoneoPay\Webhooks\Stubs\Persister\ActivityPersisterStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Activities\ActivityFactory
 */
class ActivityFactoryTest extends TestCase
{
    /**
     * Returns the instance under test.
     *
     * @param \EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface $activityPersister
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface $dispatcher
     * @param \EoneoPay\Webhooks\Payload\Interfaces\PayloadManagerInterface $payloadManager
     *
     * @return \EoneoPay\Webhooks\Activities\ActivityFactory
     */
    public function getManager(
        ActivityPersisterInterface $activityPersister,
        EventDispatcherInterface $dispatcher,
        PayloadManagerInterface $payloadManager
    ): ActivityFactory {
        return new ActivityFactory(
            $activityPersister,
            $dispatcher,
            $payloadManager
        );
    }

    /**
     * Test send method
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testSend(): void
    {
        $occurredAt = new DateTime('2011-01-01T00:00:00');

        $activityData = new ActivityDataStub();

        $expectedEvent = [5];
        $expectedActivity = [
            [
                'activityKey' => 'activity.constant',
                'entity' => $activityData->getPrimaryEntity(),
                'occurredAt' => $occurredAt,
                'payload' => [
                    'payload' => 'wot'
                ]
            ]
        ];

        $activityPersister = new ActivityPersisterStub();
        $activityPersister->setNextSequence(5);
        $dispatcher = new EventDispatcherStub();
        $payloadManager = new PayloadManagerStub();
        $payloadManager->addPayload(['payload' => 'wot']);

        $manager = $this->getManager(
            $activityPersister,
            $dispatcher,
            $payloadManager
        );

        $manager->send(
            $activityData,
            $occurredAt
        );

        self::assertSame($expectedActivity, $activityPersister->getSaved());
        self::assertSame($expectedEvent, $dispatcher->getActivityCreated());
    }

    /**
     * Test send method with default time
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testSendDefaultTime(): void
    {
        // Testing default value of a $now variable. Asserted below with generous
        // delta.
        $expectedDate = new DateTime('now');

        $activityData = new ActivityDataStub();

        $activityPersister = new ActivityPersisterStub();
        $activityPersister->setNextSequence(5);
        $dispatcher = new EventDispatcherStub();
        $payloadManager = new PayloadManagerStub();
        $payloadManager->addPayload(['payload' => 'wot']);

        $manager = $this->getManager(
            $activityPersister,
            $dispatcher,
            $payloadManager
        );

        $manager->send($activityData);

        $saved = $activityPersister->getSaved();
        $activity = \reset($saved);

        static::assertArrayHasKey('occurredAt', $activity);

        // Asserts the expected date is within 10 seconds of the generated now inside the
        // service.
        static::assertEqualsWithDelta($expectedDate, $activity['occurredAt'], 10);
    }
}
