<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Persister;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceMissingException;
use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

final class WebhookPersister implements WebhookPersisterInterface
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface
     */
    private $responseHandler;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface
     */
    private $requestHandler;

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
        $webhookResponse->populate($request, $response);

        $this->responseHandler->save($webhookResponse);
    }
}
