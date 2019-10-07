<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities;

use EoneoPay\Utils\DateTime;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EntityStub;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity
 */
class ActivityTest extends BaseEntityTestCase
{
    /**
     * Tests the toArray method.
     *
     * @return void
     *
     * @throws \ReflectionException
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testToArray(): void
    {
        $expected = [
            'activity_key' => 'activity.key',
            'id' => 123,
            'occurred_at' => '2100-01-01T10:11:12Z',
            'payload' => [
                'payload',
            ],
        ];

        $activity = $this->getActivityEntity();

        self::assertSame($expected, $activity->toArray());
    }

    /**
     * Tests the misc methods.
     *
     * @return void
     *
     * @throws \ReflectionException
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testMethods(): void
    {
        $activity = $this->getActivityEntity();

        self::assertSame(123, $activity->getId());
        self::assertSame(123, $activity->getActivityId());
        self::assertSame('activity.key', $activity->getActivityKey());
        self::assertSame(['payload'], $activity->getPayload());
        self::assertSame(EntityStub::class, $activity->getPrimaryClass());
        self::assertSame('55', $activity->getPrimaryId());
        self::assertEquals(new DateTime('2100-01-01T10:11:12Z'), $activity->getOccurredAt());
    }
}
