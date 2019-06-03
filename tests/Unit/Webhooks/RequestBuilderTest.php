<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Webhooks;

use EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Webhooks\RequestBuilder;
use Psr\Http\Message\MessageInterface;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookRequestStub;
use Tests\EoneoPay\Webhooks\TestCase;
use Zend\Diactoros\StreamFactory;
use function GuzzleHttp\Psr7\str;

/**
 * @covers \EoneoPay\Webhooks\Webhooks\RequestBuilder
 */
class RequestBuilderTest extends TestCase
{
    /**
     * Data provider for successful data.
     *
     * @return mixed[]
     */
    public function getSuccessData(): iterable
    {
        $activity = new ActivityStub();
        $activity->setPayload(['payload' => 'here']);
        $webhookRequest = new WebhookRequestStub(99, [
            'activity' => $activity,
            'format' => 'json',
            'headers' => ['authorization' => 'Bearer purple'],
            'method' => 'POST',
            'url' => 'https://localhost.com/webhook/receive'
        ]);

        $expectedJsonRequest = <<<HTTP
POST /webhook/receive HTTP/1.1
authorization: Bearer purple
content-type: application/json
Host: localhost.com

{"payload":"here"}
HTTP;

        yield 'json success' => [
            'webhookRequest' => $webhookRequest,
            'expectedHttpRequest' => $expectedJsonRequest
        ];

        $webhookRequest = new WebhookRequestStub(99, [
            'activity' => $activity,
            'format' => 'xml',
            'headers' => ['authorization' => 'Bearer purple'],
            'method' => 'POST',
            'url' => 'https://localhost.com/webhook/receive'
        ]);

        $expectedXmlRequest = <<<HTTP
POST /webhook/receive HTTP/1.1
authorization: Bearer purple
content-type: application/xml
Host: localhost.com

<?xml version="1.0" encoding="UTF-8"?>
<data>
  <payload>here</payload>
</data>

HTTP;

        yield 'xml success' => [
            'webhookRequest' => $webhookRequest,
            'expectedHttpRequest' => $expectedXmlRequest
        ];
    }

    /**
     * Tests processing successfully.
     *
     * @param \EoneoPay\Webhooks\Model\WebhookRequestInterface $webhookRequest
     * @param string $expectedHttpRequest
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     *
     * @dataProvider getSuccessData
     */
    public function testBuildSuccess(WebhookRequestInterface $webhookRequest, string $expectedHttpRequest): void
    {
        $processor = $this->getBuilder();

        $request = $processor->build($webhookRequest);

        static::assertHttpString($expectedHttpRequest, $request);
    }

    /**
     * Tests processing fails with unknown request format.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function testProcessUnknownFormat(): void
    {
        $activity = new ActivityStub();
        $activity->setPayload(['payload' => 'here']);
        $webhookRequest = new WebhookRequestStub(99, [
            'activity' => $activity,
            'format' => 'unknown'
        ]);

        $this->expectException(UnknownSerialisationFormatException::class);
        $this->expectExceptionMessage('The "unknown" format is unknown.');

        $processor = $this->getBuilder();

        $processor->build($webhookRequest);
    }

    /**
     * Asserts HTTP string matches expected.
     *
     * @param string $expected
     * @param \Psr\Http\Message\MessageInterface $request
     *
     * @return void
     */
    private static function assertHttpString(string $expected, MessageInterface $request): void
    {
        $httpString = \str_replace("\r\n", "\n", str($request));

        static::assertSame($expected, $httpString);
    }

    /**
     * Builds unit under test
     *
     * @return \EoneoPay\Webhooks\Webhooks\RequestBuilder
     */
    private function getBuilder(): RequestBuilder
    {
        return new RequestBuilder(new StreamFactory());
    }
}
