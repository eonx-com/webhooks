<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Persister;

use EoneoPay\Externals\HttpClient\Response;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceMissingException;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookRequestStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookResponseStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\RequestHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\ResponseHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;
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
    public function testSave(): void
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
    }

    /**
     * Tests Save without a sequence being returned
     *
     * @return void
     */
    public function testSaveNoSequence(): void
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
     * Tests update
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $expectedHttpString = "HTTP/1.1 204 No Content\r\n\r\n";

        $request = new WebhookRequestStub(null);
        $requestHandler = new RequestHandlerStub();
        $requestHandler->setNextRequest($request);

        $response = new WebhookResponseStub();
        $responseHandler = new ResponseHandlerStub();
        $responseHandler->setNextResponse($response);

        $persister = $this->getPersister($requestHandler, $responseHandler);
        $persister->saveResponse(1, new Response(new EmptyResponse()));

        $saved = $responseHandler->getSaved();
        static::assertContains($response, $saved);
        static::assertSame($request, $response->getData()['request']);
        static::assertSame($expectedHttpString, $response->getData()['response']);
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
