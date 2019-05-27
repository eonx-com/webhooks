<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Persister;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Persister\ActivityPersister;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\ActivityHandlerStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Persister\ActivityPersister
 */
class ActivityPersisterTest extends TestCase
{
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

        $activityId = $persister->save('activity.constant', $occurredAt, ['payload']);

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
