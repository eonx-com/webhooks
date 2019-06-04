<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities;

use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\UnexpectedObjectException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest
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

        static::assertSame(123, $request->getId());
        static::assertNotNull($request->getActivity());
        static::assertSame('json', $request->getRequestFormat());
        static::assertSame(['header' => 'value'], $request->getRequestHeaders());
        static::assertSame('POST', $request->getRequestMethod());
        static::assertSame('https://localhost.com/webhook', $request->getRequestUrl());
        static::assertSame(123, $request->getSequence());
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

        static::assertSame('json', $request->getRequestFormat());
        static::assertSame(['authorization' => 'Bearer ABC123'], $request->getRequestHeaders());
        static::assertSame('POST', $request->getRequestMethod());
        static::assertSame('https://127.0.0.1/webhook', $request->getRequestUrl());
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
        $this->expectExceptionMessage('The EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest class expects a EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity for the activity property, got Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub'); // phpcs:ignore

        $request = $this->getRequestEntity();
        $request->setActivity(new ActivityStub());
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
                'id' => 'EXTERNAL_ID',
                'occurred_at' => '2100-01-01T10:11:12Z',
                'payload' => [
                    'payload'
                ]
            ],
            'id' => 'EXTERNAL_ID',
            'request_format' => 'json',
            'request_headers' => [
                'header' => 'value'
            ],
            'request_method' => 'POST',
            'request_url' => 'https://localhost.com/webhook'
        ];

        $request = $this->getRequestEntity();

        static::assertSame($expected, $request->toArray());
    }
}
