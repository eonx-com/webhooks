<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Persisters;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Persisters\ActivityPersister;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\ActivityHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EntityStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Persisters\ActivityPersister
 */
class ActivityPersisterTest extends TestCase
{
    /**
     * Tests get.
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

        self::assertSame($activity, $result);
    }

    /**
     * Tests Save.
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
            'payload' => ['payload'],
        ];

        $activityHandler = new ActivityHandlerStub();
        $persister = $this->getPersister($activityHandler);

        $activityId = $persister->save(
            'activity.constant',
            new EntityStub(),
            $occurredAt,
            ['payload']
        );

        self::assertSame(1, $activityId);

        /** @var \Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\ActivityStub $saved */
        $saved = $activityHandler->getSaved()[0];
        self::assertSame($expectedSaved, $saved->getData());
    }

    /**
     * Get instance under test.
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface|null $activityHandler
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Persisters\ActivityPersister
     */
    private function getPersister(
        ?ActivityHandlerInterface $activityHandler = null
    ): ActivityPersister {
        return new ActivityPersister(
            $activityHandler ?? new ActivityHandlerStub()
        );
    }
}
