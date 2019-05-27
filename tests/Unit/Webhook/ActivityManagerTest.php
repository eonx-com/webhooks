<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Webhook;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Activity\ActivityManager;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Payload\Interfaces\PayloadManagerInterface;
use EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface;
use Tests\EoneoPay\Webhooks\Stubs\Activity\ActivityDataStub;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\Payload\PayloadManagerStub;
use Tests\EoneoPay\Webhooks\Stubs\Persister\ActivityPersisterStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Activity\ActivityManager
 */
class ActivityManagerTest extends TestCase
{
    /**
     * Returns the instance under test.
     *
     * @param \EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface $activityPersister
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface $dispatcher
     * @param \EoneoPay\Webhooks\Payload\Interfaces\PayloadManagerInterface $payloadManager
     *
     * @return \EoneoPay\Webhooks\Activity\ActivityManager
     */
    public function getManager(
        ActivityPersisterInterface $activityPersister,
        EventDispatcherInterface $dispatcher,
        PayloadManagerInterface $payloadManager
    ): ActivityManager {
        return new ActivityManager(
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

        $expectedEvent = [5];
        $expectedActivity = [
            [
                'activityConstant' => 'activity.constant',
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
            new ActivityDataStub(),
            $occurredAt
        );

        self::assertSame($expectedActivity, $activityPersister->getSaved());
        self::assertSame($expectedEvent, $dispatcher->getActivityCreated());
    }
}
