<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\Lifecycle;

use EoneoPay\Utils\DateTime;
use Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\BaseEntityTestCase;
use Zend\Diactoros\Response\JsonResponse;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Lifecycle\Response
 */
class ResponseTest extends BaseEntityTestCase
{
    /**
     * Tests methods on the WebhookResponse.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testMiscMethods(): void
    {
        $response = $this->getResponseEntity();

        self::assertTrue($response->isSuccessful());
        self::assertSame('234', $response->getId());
        self::assertSame('234', $response->getResponseId());
    }

    /**
     * Tests populate.
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

        self::assertSame('TRUNCATED_RESPONSE', $response->getResponse());
        self::assertSame($request, $response->getRequest());
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
        $response = $this->getResponseEntity();

        $expected = new DateTime('2099-10-10');

        $response->setCreatedAt(new DateTime('2099-10-10'));

        self::assertEquals($expected, $response->getCreatedAt());
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
            'created_at' => '2099-10-10T00:00:00Z',
            'error_reason' => 'error_reason',
            'id' => '234',
            'request' => [
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
            ],
            'response' => 'RESPONSE',
            'status_code' => 204,
            'successful' => true,
        ];

        $response = $this->getResponseEntity();

        self::assertSame($expected, $response->toArray());
    }
}
