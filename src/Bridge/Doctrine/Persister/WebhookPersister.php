<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Persister;

use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceMissingException;
use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\str;

final class WebhookPersister implements WebhookPersisterInterface
{
    /**
     * The maximum number of bytes of the response that we'll save.
     */
    private const MAX_RESPONSE_BYTES = 102400;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface
     */
    private $responseHandler;

    /**
     * Constructor
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface $requestHandler
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface $responseHandler
     */
    public function __construct(
        RequestHandlerInterface $requestHandler,
        ResponseHandlerInterface $responseHandler
    ) {
        $this->requestHandler = $requestHandler;
        $this->responseHandler = $responseHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function saveRequest(ActivityInterface $activity, SubscriptionInterface $subscription): int
    {
        $request = $this->requestHandler->create();
        $request->populate($activity, $subscription);

        $this->requestHandler->save($request);

        if ($request->getSequence() === null) {
            throw new WebhookSequenceMissingException('The request handler didnt return a usable sequence number');
        }

        return $request->getSequence();
    }

    /**
     * {@inheritdoc}
     */
    public function saveResponse(int $sequence, ResponseInterface $response): void
    {
        $request = $this->requestHandler->getBySequence($sequence);

        $webhookResponse = $this->responseHandler->createNewWebhookResponse();

        $stringResponse = $this->getTruncatedBody($response);
        $webhookResponse->populate($request, $stringResponse);

        $this->responseHandler->save($webhookResponse);
    }

    /**
     * Truncates and returns a string of the response
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return string
     */
    private function getTruncatedBody(ResponseInterface $response): string
    {
        return \mb_strimwidth(str($response), 0, static::MAX_RESPONSE_BYTES);
    }
}
