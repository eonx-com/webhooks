<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Persister;

use EoneoPay\Externals\HttpClient\Exceptions\InvalidApiResponseException;
use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceMissingException;
use EoneoPay\Webhooks\Models\ActivityInterface;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;
use EoneoPay\Webhooks\Persisters\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use function GuzzleHttp\Psr7\str;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) Coupling required to handle webhook request and response persistence
 */
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
     * Constructor.
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
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function saveRequest(ActivityInterface $activity, SubscriptionInterface $subscription): int
    {
        $request = $this->requestHandler->create();
        $request->setCreatedAt(new DateTime());
        $request->populate($activity, $subscription);

        $this->requestHandler->save($request);

        if ($request->getSequence() === null) {
            throw new WebhookSequenceMissingException('The request handler didnt return a usable sequence number');
        }

        return $request->getSequence();
    }

    /**
     * {@inheritdoc}
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function saveResponse(WebhookRequestInterface $webhookRequest, ResponseInterface $response): void
    {
        $webhookResponse = $this->responseHandler->createNewWebhookResponse();
        $webhookResponse->setCreatedAt(new DateTime());
        $webhookResponse->setSuccessful(true);

        $stringResponse = $this->getTruncatedBody($response);
        $webhookResponse->populate($webhookRequest, $response, $stringResponse);

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
             * @var \EoneoPay\Externals\HttpClient\Exceptions\InvalidApiResponseException|\GuzzleHttp\Exception\RequestException $exception
             *
             * @see https://youtrack.jetbrains.com/issue/WI-37859 - typehint required until PhpStorm recognises === check
             */
            // phpcs:enable
            $response = $exception->getResponse();
        }

        if ($response !== null) {
            $webhookResponse->populate(
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
     * Truncates and returns a string of the response.
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
