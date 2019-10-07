<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Webhooks\Request;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\UnexpectedObjectException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Webhooks\Request
 */
class WebhookRequestTest extends BaseEntityTestCase
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
     * Tests that the setActivity function will throw when trying to
     * set the wrong type of Activity.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testSetActivityWrongEntity(): void
    {
        $this->expectException(UnexpectedObjectException::class);
        $this->expectExceptionMessage(\sprintf(
            'The %s class expects a %s for the activity property, got %s',
            Request::class,
            Activity::class,
            ActivityStub::class
        ));

        $request = $this->getRequestEntity();
        $request->setActivity(new ActivityStub());
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
