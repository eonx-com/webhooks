<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Webhooks;

use EoneoPay\Externals\HttpClient\Exceptions\InvalidApiResponseException;
use EoneoPay\Externals\HttpClient\Exceptions\NetworkException;
use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Externals\HttpClient\Response;
use EoneoPay\Webhooks\Exceptions\InvalidRequestException;
use EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Webhooks\RequestProcessor;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookRequestStub;
use Tests\EoneoPay\Webhooks\Stubs\Externals\HttpClientStub;
use Tests\EoneoPay\Webhooks\Stubs\Externals\ThrowingHttpClientStub;
use Tests\EoneoPay\Webhooks\Stubs\Persister\WebhookPersisterStub;
use Tests\EoneoPay\Webhooks\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response as PsrResponse;
use Zend\Diactoros\StreamFactory;
use function GuzzleHttp\Psr7\str;

/**
 * @covers \EoneoPay\Webhooks\Webhooks\RequestProcessor
 */
class RequestProcessorTest extends TestCase
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
     * Tests exception when request has no sequence number.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function testProcessNetworkException(): void
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

        $exception = new NetworkException(new Request(), new RuntimeException('message'));
        $httpClient = new ThrowingHttpClientStub($exception);
        $persister = new WebhookPersisterStub();
        $processor = $this->getProcessor($httpClient, $persister);

        $processor->process($webhookRequest);

        $updates = $persister->getUpdates();
        static::assertCount(1, $updates);
        static::assertSame($exception, $updates[0]['exception'] ?? null);
        static::assertSame($webhookRequest->getSequence(), $updates[0]['sequence'] ?? null);
    }

    /**
     * Tests exception when request has no sequence number.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function testProcessInvalidApiException(): void
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

        $exception = new InvalidApiResponseException(new Request(), new Response(new PsrResponse()));
        $httpClient = new ThrowingHttpClientStub($exception);
        $persister = new WebhookPersisterStub();
        $processor = $this->getProcessor($httpClient, $persister);

        $processor->process($webhookRequest);

        $updates = $persister->getUpdates();
        static::assertCount(1, $updates);
        static::assertSame($exception, $updates[0]['exception'] ?? null);
        static::assertSame($webhookRequest->getSequence(), $updates[0]['sequence'] ?? null);
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
    public function testProcessSuccess(WebhookRequestInterface $webhookRequest, string $expectedHttpRequest): void
    {
        $httpClient = new HttpClientStub();
        $persister = new WebhookPersisterStub();
        $processor = $this->getProcessor($httpClient, $persister);

        $processor->process($webhookRequest);

        $httpRequest = $httpClient->getRequests()[0]['request'] ?? null;

        static::assertInstanceOf(RequestInterface::class, $httpRequest);
        static::assertHttpString($expectedHttpRequest, $httpRequest);

        $updates = $persister->getUpdates();
        static::assertCount(1, $updates);
        static::assertSame($webhookRequest->getSequence(), $updates[0]['sequence'] ?? null);
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

        $httpClient = new HttpClientStub();
        $processor = $this->getProcessor($httpClient);

        $processor->process($webhookRequest);
    }

    /**
     * Tests exception when request has no sequence number.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     */
    public function testProcessWithoutSequence(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('The webhookRequest does not have a sequence number.');

        $processor = $this->getProcessor();

        $processor->process(new WebhookRequestStub(null));
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
     * Returns instance under test.
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface|null $client
     * @param \EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface|null $webhookPersister
     *
     * @return \EoneoPay\Webhooks\Webhooks\RequestProcessor
     */
    private function getProcessor(
        ?ClientInterface $client = null,
        ?WebhookPersisterInterface $webhookPersister = null
    ): RequestProcessor {
        return new RequestProcessor(
            $client ?? new HttpClientStub(),
            new StreamFactory(),
            $webhookPersister ?? new WebhookPersisterStub()
        );
    }
}
