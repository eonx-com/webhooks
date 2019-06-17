<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\UnexpectedObjectException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookRequestStub;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse
 */
class WebhookResponseTest extends BaseEntityTestCase
{
    /**
     * Tests methods on the WebhookResponse
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testMiscMethods(): void
    {
        $response = $this->getResponseEntity();

        static::assertTrue($response->isSuccessful());
        static::assertSame(234, $response->getId());
    }

    /**
     * Tests populate
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testPopulate(): void
    {
        $request = $this->getRequestEntity();
        $response = $this->getResponseEntity();

        $response->populate(
            $request,
            new JsonResponse(['response' => 'ok']),
            'TRUNCATED_RESPONSE'
        );

        static::assertSame('TRUNCATED_RESPONSE', $response->getResponse());
        static::assertSame($request, $response->getRequest());
    }

    /**
     * Test if setCreatedAt method sets the created at date and it can be retrieved by getCreatedAt
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testSetCreatedAt(): void
    {
        $response = $this->getResponseEntity();

        $expected = new DateTime('2099-10-10');

        $response->setCreatedAt(new DateTime('2099-10-10'));

        self::assertEquals($expected, $response->getCreatedAt());
    }

    /**
     * Tests setRequest behaviour when receiving a WebhookRequestInterface object that
     * isnt the one we want.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testSetRequestWrongEntity(): void
    {
        $this->expectException(UnexpectedObjectException::class);
        $this->expectExceptionMessage('The EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookResponse class expects a EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest for the request property, got Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookRequestStub'); // phpcs:ignore

        $response = $this->getResponseEntity();
        $response->setRequest(new WebhookRequestStub(1));
    }

    /**
     * Tests the toArray method
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testToArray(): void
    {
        $expected = [
            'created_at' => null,
            'error_reason' => 'error_reason',
            'id' => 234,
            'request' => [
                'activity' => [
                    'activity_key' => 'activity.key',
                    'id' => 123,
                    'occurred_at' => '2100-01-01T10:11:12Z',
                    'payload' => [
                        'payload'
                    ]
                ],
                'created_at' => null,
                'id' => 123,
                'request_format' => 'json',
                'request_headers' => [
                    'header' => 'value'
                ],
                'request_method' => 'POST',
                'request_url' => 'https://localhost.com/webhook'
            ],
            'status_code' => 204,
            'successful' => true
        ];

        $response = $this->getResponseEntity();

        static::assertSame($expected, $response->toArray());
    }
}
