<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Persisters;

use EoneoPay\Externals\HttpClient\Exceptions\NetworkException;
use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Persisters\WebhookPersister;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceMissingException;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\Lifecycle\RequestStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\Lifecycle\ResponseStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\RequestHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\ResponseHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscriptions\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Persisters\WebhookPersister
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) required for testing
 */
class WebhookPersisterTest extends TestCase
{
    /**
     * Tests Save.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testSaveRequest(): void
    {
        $activity = new ActivityStub();
        $subscription = new SubscriptionStub();

        $request = new RequestStub(1);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $persister = $this->getPersister($requestHandler);

        $sequence = $persister->saveRequest($activity, $subscription);

        self::assertSame(1, $sequence);
        self::assertContains($request, $requestHandler->getSaved());
        self::assertSame($activity, $request->getData()['activity']);
        // make sure date is set in persister
        self::assertInstanceOf(\DateTime::class, $request->getCreatedAt());
        self::assertEqualsWithDelta(new DateTime('now'), $request->getCreatedAt(), 10);
    }

    /**
     * Tests Save without a sequence being returned.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testSaveRequestNoSequence(): void
    {
        $this->expectException(WebhookSequenceMissingException::class);

        $activity = new ActivityStub();
        $subscription = new SubscriptionStub();

        $request = new RequestStub(null);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $persister = $this->getPersister($requestHandler);

        $persister->saveRequest($activity, $subscription);
    }

    /**
     * Tests saveResponse.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testSaveResponse(): void
    {
        $response = new EmptyResponse();
        $expectedHttpString = "HTTP/1.1 204 No Content\r\n\r\n";

        $request = new RequestStub(null);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $webhookResponse = new ResponseStub();
        $responseHandler = new ResponseHandlerStub();
        $responseHandler->setNextResponse($webhookResponse);

        $persister = $this->getPersister($requestHandler, $responseHandler);
        $persister->saveResponse($request, $response);

        $saved = $responseHandler->getSaved();
        self::assertContains($webhookResponse, $saved);
        self::assertSame($request, $webhookResponse->getData()['request']);
        self::assertSame($response, $webhookResponse->getData()['response']);
        self::assertSame($expectedHttpString, $webhookResponse->getData()['truncatedResponse']);
        // make sure date is set in persister
        self::assertInstanceOf(\DateTime::class, $webhookResponse->getCreatedAt());
        self::assertEqualsWithDelta(new DateTime('now'), $webhookResponse->getCreatedAt(), 10);
    }

    /**
     * Tests saveResponseException.
     *
     * @return void
     */
    public function testSaveResponseNetworkException(): void
    {
        $exception = new NetworkException(new Request(), new Exception('Message'));

        $request = new RequestStub(null);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $webhookResponse = new ResponseStub();
        $responseHandler = new ResponseHandlerStub();
        $responseHandler->setNextResponse($webhookResponse);

        $persister = $this->getPersister($requestHandler, $responseHandler);
        $persister->saveResponseException($request, $exception);

        $saved = $responseHandler->getSaved();
        self::assertContains($webhookResponse, $saved);
        self::assertFalse($webhookResponse->isSuccessful());
        self::assertSame('Message', $webhookResponse->getData()['errorReason']);
    }

    /**
     * Tests saveResponseException.
     *
     * @return void
     */
    public function testSaveResponseRequestExceptionNoResponse(): void
    {
        $exception = new RequestException('Broken', new Request());

        $request = new RequestStub(null);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $webhookResponse = new ResponseStub();
        $responseHandler = new ResponseHandlerStub();
        $responseHandler->setNextResponse($webhookResponse);

        $persister = $this->getPersister($requestHandler, $responseHandler);
        $persister->saveResponseException($request, $exception);

        $saved = $responseHandler->getSaved();
        self::assertContains($webhookResponse, $saved);
        self::assertFalse($webhookResponse->isSuccessful());
        self::assertSame('Broken', $webhookResponse->getData()['errorReason']);
    }

    /**
     * Tests saveResponseException.
     *
     * @return void
     */
    public function testSaveResponseRequestExceptionWithResponse(): void
    {
        $exception = new RequestException('Broken', new Request(), new EmptyResponse(400));

        $request = new RequestStub(null);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $webhookResponse = new ResponseStub();
        $responseHandler = new ResponseHandlerStub();
        $responseHandler->setNextResponse($webhookResponse);

        $persister = $this->getPersister($requestHandler, $responseHandler);
        $persister->saveResponseException($request, $exception);

        $saved = $responseHandler->getSaved();
        self::assertContains($webhookResponse, $saved);
        self::assertFalse($webhookResponse->isSuccessful());
        self::assertSame('Broken', $webhookResponse->getData()['errorReason']);
    }

    /**
     * Get instance under test.
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface|null $requestHandler
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface|null $responseHandler
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Persisters\WebhookPersister
     */
    private function getPersister(
        ?RequestHandlerInterface $requestHandler = null,
        ?ResponseHandlerInterface $responseHandler = null
    ): WebhookPersister {
        return new WebhookPersister(
            $requestHandler ?? new RequestHandlerStub(),
            $responseHandler ?? new ResponseHandlerStub()
        );
    }
}
