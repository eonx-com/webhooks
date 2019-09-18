<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Persister;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Persister\ActivityPersister;
use EoneoPay\Webhooks\Bridge\Laravel\Exceptions\ActivityNotFoundException;
use GuzzleHttp\Promise\EachPromise;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\ActivityHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EntityStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Persister\ActivityPersister
 */
class ActivityPersisterTest extends TestCase
{
    /**
     * Test that adding sequence to activity payload returns payload with
     * activity sequence.
     *
     * @return void
     */
    public function testAddSequenceToPayload(): void
    {
        $activity = new ActivityStub();
        $activity->setPayload(['key' => 'value']);
        $activityHandler = new ActivityHandlerStub();
        $activityHandler->setNext($activity);
        $expectedPayload = [
            'key' => 'value',
            '_sequence' => 5
        ];
        $persister = $this->getPersister($activityHandler);

        $persister->addSequenceToPayload(5);

        self::assertSame($expectedPayload, $activity->getPayload());
    }

    /**
     * Test that adding sequence to activity payload throws exception when invalid or
     * unknown activity id is provided.
     *
     * @return void
     */
    public function testAddSequenceToPayloadThrowsNotFoundException(): void
    {
        $activityHandler = new ActivityHandlerStub();
        $persister = $this->getPersister($activityHandler);

        $this->expectException(ActivityNotFoundException::class);
        $this->expectExceptionMessage('No activity "111" found to add sequence.');

        $persister->addSequenceToPayload(111);
    }

    /**
     * Tests get
     *
     * @return void
     */
    public function testGet(): void
    {
        $activity = new ActivityStub();
        $activityHandler = new ActivityHandlerStub();
        $activityHandler->setNext($activity);

        $persister = $this->getPersister($activityHandler);

        $result = $persister->get(5);

        static::assertSame($activity, $result);
    }

    /**
     * Tests Save
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testSave(): void
    {
        $occurredAt = new DateTime();

        $expectedSaved = [
            'constant' => 'activity.constant',
            'occurredAt' => $occurredAt,
            'payload' => ['payload']
        ];

        $activityHandler = new ActivityHandlerStub();
        $persister = $this->getPersister($activityHandler);

        $activityId = $persister->save(
            'activity.constant',
            new EntityStub(),
            $occurredAt,
            ['payload']
        );

        static::assertSame(1, $activityId);

        /** @var \Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub $saved */
        $saved = $activityHandler->getSaved()[0];
        static::assertSame($expectedSaved, $saved->getData());
    }

    /**
     * Get instance under test
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface|null $activityHandler
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Persister\ActivityPersister
     */
    private function getPersister(
        ?ActivityHandlerInterface $activityHandler = null
    ): ActivityPersister {
        return new ActivityPersister(
            $activityHandler ?? new ActivityHandlerStub()
        );
    }
}
