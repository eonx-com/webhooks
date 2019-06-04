<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Persister;

use EoneoPay\Externals\HttpClient\Exceptions\InvalidApiResponseException;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceMissingException;
use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Throwable;
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
    public function saveResponse(WebhookRequestInterface $webhookRequest, ResponseInterface $response): void
    {
        $webhookResponse = $this->responseHandler->createNewWebhookResponse();

        $stringResponse = $this->getTruncatedBody($response);
        $webhookResponse->populateResponse($webhookRequest, $response, $stringResponse);

        $this->responseHandler->save($webhookResponse);
    }

    /**
     * {@inheritdoc}
     */
    public function saveResponseException(
        WebhookRequestInterface $webhookRequest,
        Throwable $exception
    ) {
        $webhookResponse = $this->responseHandler->createNewWebhookResponse();
        $response = null;

        if (($exception instanceof RequestException) === true ||
            ($exception instanceof InvalidApiResponseException) === true) {
            // phpcs:disable Generic.Files.LineLength
            /**
             * @var \GuzzleHttp\Exception\RequestException|\EoneoPay\Externals\HttpClient\Exceptions\InvalidApiResponseException $exception
             *
             * @see https://youtrack.jetbrains.com/issue/WI-37859 - typehint required until PhpStorm recognises === check
             */
            // phpcs:enable
            $response = $exception->getResponse();
        }

        if ($response !== null) {
            $webhookResponse->populateResponse(
                $webhookRequest,
                $response,
                $this->getTruncatedBody($response)
            );
        }

        $webhookResponse->setSuccessful(false);
        $webhookResponse->setErrorReason($exception->getMessage());

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
