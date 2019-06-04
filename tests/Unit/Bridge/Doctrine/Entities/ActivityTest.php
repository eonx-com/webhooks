<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities;

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
            'id' => 'EXTERNAL_ID',
            'occurred_at' => '2100-01-01T10:11:12Z',
            'payload' => [
                'payload'
            ]
        ];

        $activity = $this->getActivityEntity();

        static::assertSame($expected, $activity->toArray());
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

        static::assertSame(123, $activity->getId());
        static::assertSame(123, $activity->getActivityId());
        static::assertSame('activity.key', $activity->getActivityKey());
        static::assertSame(['payload'], $activity->getPayload());
        static::assertSame(EntityStub::class, $activity->getPrimaryClass());
        static::assertSame('55', $activity->getPrimaryId());
    }
}
