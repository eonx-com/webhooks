<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use EoneoPay\Externals\HttpClient\Exceptions\InvalidApiResponseException;
use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Utils\XmlConverter;
use EoneoPay\Webhooks\Exceptions\InvalidRequestException;
use EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Zend\Diactoros\Request;

class RequestProcessor implements RequestProcessorInterface
{
    /**
     * @var \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface
     */
    private $client;

    /**
     * @var \Psr\Http\Message\StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * @var \EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface
     */
    private $webhookPersister;

    /**
     * Constructor
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface $client
     * @param \Psr\Http\Message\StreamFactoryInterface $streamFactory
     * @param \EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface $webhookPersister
     */
    public function __construct(
        ClientInterface $client,
        StreamFactoryInterface $streamFactory,
        WebhookPersisterInterface $webhookPersister
    ) {
        $this->client = $client;
        $this->streamFactory = $streamFactory;
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

        $request = $this->buildRequest($webhookRequest);

        try {
            $response = $this->client->sendRequest($request);
            $this->webhookPersister->saveResponse($webhookRequest, $response);
        } catch (NetworkExceptionInterface $networkException) {
            $this->webhookPersister->saveResponseException($webhookRequest, $networkException);
        } catch (InvalidApiResponseException $exception) {
            $this->webhookPersister->saveResponseException($webhookRequest, $exception);
        }
    }

    /**
     * Builds a RequestInterface to be sent.
     *
     * @param \EoneoPay\Webhooks\Model\WebhookRequestInterface $webhookRequest
     *
     * @return \Psr\Http\Message\RequestInterface
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     * @throws \EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException
     */
    private function buildRequest(WebhookRequestInterface $webhookRequest): RequestInterface
    {
        $body = $webhookRequest->getActivity()->getPayload();
        $headers = $webhookRequest->getRequestHeaders();
        $method = $webhookRequest->getRequestMethod();
        $uri = $webhookRequest->getRequestUrl();

        switch ($webhookRequest->getRequestFormat()) {
            case 'json': // @codeCoverageIgnore
                return new Request(
                    $uri,
                    $method,
                    $this->streamFactory->createStream(\json_encode($body)),
                    \array_merge($headers, [
                        'content-type' => 'application/json'
                    ])
                );

            case 'xml': // @codeCoverageIgnore
                return new Request(
                    $uri,
                    $method,
                    $this->streamFactory->createStream(
                        (new XmlConverter())->arrayToXml($body)
                    ),
                    \array_merge($headers, [
                        'content-type' => 'application/xml'
                    ])
                );
        }

        throw new UnknownSerialisationFormatException(\sprintf(
            'The "%s" format is unknown.',
            $webhookRequest->getRequestFormat()
        ));
    }
}
