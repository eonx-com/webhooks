<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use EoneoPay\Externals\HttpClient\Exceptions\InvalidApiResponseException;
use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Webhooks\Exceptions\InvalidRequestException;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestBuilderInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface;
use Psr\Http\Client\NetworkExceptionInterface;

class RequestProcessor implements RequestProcessorInterface
{
    /**
     * @var \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface
     */
    private $client;

    /**
     * @var \EoneoPay\Webhooks\Webhooks\Interfaces\RequestBuilderInterface
     */
    private $requestBuilder;

    /**
     * @var \EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface
     */
    private $webhookPersister;

    /**
     * Constructor
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface $client
     * @param \EoneoPay\Webhooks\Webhooks\Interfaces\RequestBuilderInterface $requestBuilder
     * @param \EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface $webhookPersister
     */
    public function __construct(
        ClientInterface $client,
        RequestBuilderInterface $requestBuilder,
        WebhookPersisterInterface $webhookPersister
    ) {
        $this->client = $client;
        $this->requestBuilder = $requestBuilder;
        $this->webhookPersister = $webhookPersister;
    }

    /**
     * Processes a request and emits the webhook request.
     *
     * @param \EoneoPay\Webhooks\Model\WebhookRequestInterface $webhookRequest
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     * @throws \EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException
     */
    public function process(WebhookRequestInterface $webhookRequest): void
    {
        if ($webhookRequest->getSequence() === null) {
            throw new InvalidRequestException('The webhookRequest does not have a sequence number.');
        }

        $request = $this->requestBuilder->build($webhookRequest);

        try {
            $response = $this->client->sendRequest($request);
            $this->webhookPersister->saveResponse($webhookRequest, $response);
        } catch (NetworkExceptionInterface $networkException) {
            $this->webhookPersister->saveResponseException($webhookRequest, $networkException);
        } catch (InvalidApiResponseException $exception) {
            $this->webhookPersister->saveResponseException($webhookRequest, $exception);
        }
    }
}
