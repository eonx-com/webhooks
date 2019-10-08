<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\Lifecycle;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity;
use Tests\EoneoPay\Webhooks\Stubs\Subscriptions\SubscriptionStub;
use Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\BaseEntityTestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Lifecycle\Request
 */
class RequestTest extends BaseEntityTestCase
{
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
        $request = $this->getRequestEntity();

        self::assertSame(123, $request->getId());
        self::assertInstanceOf(Activity::class, $request->getActivity());
        self::assertSame('json', $request->getRequestFormat());
        self::assertSame(['header' => 'value'], $request->getRequestHeaders());
        self::assertSame('POST', $request->getRequestMethod());
        self::assertSame('https://localhost.com/webhook', $request->getRequestUrl());
        self::assertSame(123, $request->getSequence());
        self::assertSame('123', $request->getExternalId());
    }

    /**
     * Tests the populate method.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testPopulate(): void
    {
        $request = $this->getRequestEntity();
        $activity = $this->getActivityEntity();

        $request->populate($activity, new SubscriptionStub());

        self::assertSame('json', $request->getRequestFormat());
        self::assertSame(['authorization' => 'Bearer ABC123'], $request->getRequestHeaders());
        self::assertSame('POST', $request->getRequestMethod());
        self::assertSame('https://127.0.0.1/webhook', $request->getRequestUrl());
    }

    /**
     * Test if setCreatedAt method sets the created at date and it can be retrieved by getCreatedAt.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testSetCreatedAt(): void
    {
        $request = $this->getRequestEntity();

        $expected = new DateTime('2099-10-10');

        $request->setCreatedAt(new DateTime('2099-10-10'));

        self::assertEquals($expected, $request->getCreatedAt());
    }

    /**
     * Tests the toArray method.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testToArray(): void
    {
        $expected = [
            'activity' => [
                'activity_key' => 'activity.key',
                'id' => 123,
                'occurred_at' => '2100-01-01T10:11:12Z',
                'payload' => [
                    'payload',
                ],
            ],
            'created_at' => '2099-10-10T00:00:00Z',
            'id' => 123,
            'request_format' => 'json',
            'request_headers' => [
                'header' => 'value',
            ],
            'request_method' => 'POST',
            'request_url' => 'https://localhost.com/webhook',
        ];

        $request = $this->getRequestEntity();

        self::assertSame($expected, $request->toArray());
    }
}
