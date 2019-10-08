<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Webhooks;

use EoneoPay\Externals\HttpClient\Exceptions\InvalidApiResponseException;
use EoneoPay\Externals\HttpClient\Exceptions\NetworkException;
use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Externals\HttpClient\Response;
use EoneoPay\Webhooks\Exceptions\InvalidRequestException;
use EoneoPay\Webhooks\Persisters\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Webhooks\RequestProcessor;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\Lifecycle\RequestStub;
use Tests\EoneoPay\Webhooks\Stubs\Externals\HttpClientStub;
use Tests\EoneoPay\Webhooks\Stubs\Externals\ThrowingHttpClientStub;
use Tests\EoneoPay\Webhooks\Stubs\Persisters\WebhookPersisterStub;
use Tests\EoneoPay\Webhooks\Stubs\Webhooks\RequestBuilderStub;
use Tests\EoneoPay\Webhooks\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response as PsrResponse;

/**
 * @covers \EoneoPay\Webhooks\Webhooks\RequestProcessor
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) required to test RequestProcessor
 */
class RequestProcessorTest extends TestCase
{
    /**
     * Tests exception when request has no sequence number.
     *
     * @return void
     */
    public function testProcessInvalidApiException(): void
    {
        $activity = new ActivityStub();
        $activity->setPayload(['payload' => 'here']);
        $webhookRequest = new RequestStub(99, [
            'activity' => $activity,
            'format' => 'json',
            'headers' => ['authorization' => 'Bearer purple'],
            'method' => 'POST',
            'url' => 'https://localhost.com/webhook/receive',
        ]);

        $exception = new InvalidApiResponseException(new Request(), new Response(new PsrResponse()));
        $httpClient = new ThrowingHttpClientStub($exception);
        $persister = new WebhookPersisterStub();
        $processor = $this->getProcessor($httpClient, $persister);

        $processor->process($webhookRequest);

        $updates = $persister->getUpdates();
        self::assertCount(1, $updates);
        self::assertSame($exception, $updates[0]['exception'] ?? null);
        self::assertSame($webhookRequest->getSequence(), $updates[0]['sequence'] ?? null);
    }

    /**
     * Tests exception when request has no sequence number.
     *
     * @return void
     */
    public function testProcessNetworkException(): void
    {
        $activity = new ActivityStub();
        $activity->setPayload(['payload' => 'here']);
        $webhookRequest = new RequestStub(99, [
            'activity' => $activity,
            'format' => 'json',
            'headers' => ['authorization' => 'Bearer purple'],
            'method' => 'POST',
            'url' => 'https://localhost.com/webhook/receive',
        ]);

        $exception = new NetworkException(new Request(), new RuntimeException('message'));
        $httpClient = new ThrowingHttpClientStub($exception);
        $persister = new WebhookPersisterStub();
        $processor = $this->getProcessor($httpClient, $persister);

        $processor->process($webhookRequest);

        $updates = $persister->getUpdates();
        self::assertCount(1, $updates);
        self::assertSame($exception, $updates[0]['exception'] ?? null);
        self::assertSame($webhookRequest->getSequence(), $updates[0]['sequence'] ?? null);
    }

    /**
     * Tests processing successfully.
     *
     * @return void
     */
    public function testProcessSuccess(): void
    {
        $activity = new ActivityStub();
        $activity->setPayload(['payload' => 'here']);
        $webhookRequest = new RequestStub(99, [
            'activity' => $activity,
            'format' => 'json',
            'headers' => ['authorization' => 'Bearer purple'],
            'method' => 'POST',
            'url' => 'https://localhost.com/webhook/receive',
        ]);

        $httpClient = new HttpClientStub();
        $persister = new WebhookPersisterStub();
        $processor = $this->getProcessor($httpClient, $persister);

        $processor->process($webhookRequest);

        $httpRequest = $httpClient->getRequests()[0]['request'] ?? null;

        self::assertInstanceOf(RequestInterface::class, $httpRequest);

        $updates = $persister->getUpdates();
        self::assertCount(1, $updates);
        self::assertSame($webhookRequest->getSequence(), $updates[0]['sequence'] ?? null);
    }

    /**
     * Tests exception when request has no sequence number.
     *
     * @return void
     */
    public function testProcessWithoutSequence(): void
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('The webhookRequest does not have a sequence number.');

        $processor = $this->getProcessor();

        $processor->process(new RequestStub(null));
    }

    /**
     * Returns instance under test.
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface|null $client
     * @param \EoneoPay\Webhooks\Persisters\Interfaces\WebhookPersisterInterface|null $webhookPersister
     *
     * @return \EoneoPay\Webhooks\Webhooks\RequestProcessor
     */
    private function getProcessor(
        ?ClientInterface $client = null,
        ?WebhookPersisterInterface $webhookPersister = null
    ): RequestProcessor {
        return new RequestProcessor(
            $client ?? new HttpClientStub(),
            new RequestBuilderStub(),
            $webhookPersister ?? new WebhookPersisterStub()
        );
    }
}
