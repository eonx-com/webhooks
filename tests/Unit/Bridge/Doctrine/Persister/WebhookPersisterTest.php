<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Persister;

use EoneoPay\Externals\HttpClient\Exceptions\NetworkException;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceMissingException;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookRequestStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookResponseStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\RequestHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\ResponseHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) required for testing
 */
class WebhookPersisterTest extends TestCase
{
    /**
     * Tests Save
     *
     * @return void
     */
    public function testSaveRequest(): void
    {
        $activity = new ActivityStub();
        $subscription = new SubscriptionStub();

        $request = new WebhookRequestStub(1);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $persister = $this->getPersister($requestHandler);

        $sequence = $persister->saveRequest($activity, $subscription);

        static::assertSame(1, $sequence);
        static::assertContains($request, $requestHandler->getSaved());
        static::assertSame($activity, $request->getData()['activity']);
        // make sure date is set in persister
        static::assertInstanceOf(\DateTime::class, $request->getCreatedAt());
    }

    /**
     * Tests Save without a sequence being returned
     *
     * @return void
     */
    public function testSaveRequestNoSequence(): void
    {
        $this->expectException(WebhookSequenceMissingException::class);

        $activity = new ActivityStub();
        $subscription = new SubscriptionStub();

        $request = new WebhookRequestStub(null);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $persister = $this->getPersister($requestHandler);

        $persister->saveRequest($activity, $subscription);
    }

    /**
     * Tests saveResponse
     *
     * @return void
     */
    public function testSaveResponse(): void
    {
        $response = new EmptyResponse();
        $expectedHttpString = "HTTP/1.1 204 No Content\r\n\r\n";

        $request = new WebhookRequestStub(null);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $webhookResponse = new WebhookResponseStub();
        $responseHandler = new ResponseHandlerStub();
        $responseHandler->setNextResponse($webhookResponse);

        $persister = $this->getPersister($requestHandler, $responseHandler);
        $persister->saveResponse($request, $response);

        $saved = $responseHandler->getSaved();
        static::assertContains($webhookResponse, $saved);
        static::assertSame($request, $webhookResponse->getData()['request']);
        static::assertSame($response, $webhookResponse->getData()['response']);
        static::assertSame($expectedHttpString, $webhookResponse->getData()['truncatedResponse']);
        // make sure date is set in persister
        static::assertInstanceOf(\DateTime::class, $webhookResponse->getCreatedAt());
    }

    /**
     * Tests saveResponseException
     *
     * @return void
     */
    public function testSaveResponseNetworkException(): void
    {
        $exception = new NetworkException(new Request(), new Exception('Message'));

        $request = new WebhookRequestStub(null);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $webhookResponse = new WebhookResponseStub();
        $responseHandler = new ResponseHandlerStub();
        $responseHandler->setNextResponse($webhookResponse);

        $persister = $this->getPersister($requestHandler, $responseHandler);
        $persister->saveResponseException($request, $exception);

        $saved = $responseHandler->getSaved();
        static::assertContains($webhookResponse, $saved);
        static::assertFalse($webhookResponse->isSuccessful());
        static::assertSame('Message', $webhookResponse->getData()['errorReason']);
    }

    /**
     * Tests saveResponseException
     *
     * @return void
     */
    public function testSaveResponseRequestExceptionNoResponse(): void
    {
        $exception = new RequestException('Broken', new Request());

        $request = new WebhookRequestStub(null);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $webhookResponse = new WebhookResponseStub();
        $responseHandler = new ResponseHandlerStub();
        $responseHandler->setNextResponse($webhookResponse);

        $persister = $this->getPersister($requestHandler, $responseHandler);
        $persister->saveResponseException($request, $exception);

        $saved = $responseHandler->getSaved();
        static::assertContains($webhookResponse, $saved);
        static::assertFalse($webhookResponse->isSuccessful());
        static::assertSame('Broken', $webhookResponse->getData()['errorReason']);
    }

    /**
     * Tests saveResponseException
     *
     * @return void
     */
    public function testSaveResponseRequestExceptionWithResponse(): void
    {
        $exception = new RequestException('Broken', new Request(), new EmptyResponse(400));

        $request = new WebhookRequestStub(null);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $webhookResponse = new WebhookResponseStub();
        $responseHandler = new ResponseHandlerStub();
        $responseHandler->setNextResponse($webhookResponse);

        $persister = $this->getPersister($requestHandler, $responseHandler);
        $persister->saveResponseException($request, $exception);

        $saved = $responseHandler->getSaved();
        static::assertContains($webhookResponse, $saved);
        static::assertFalse($webhookResponse->isSuccessful());
        static::assertSame('Broken', $webhookResponse->getData()['errorReason']);
    }

    /**
     * Get instance under test
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface|null $requestHandler
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface|null $responseHandler
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister
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
