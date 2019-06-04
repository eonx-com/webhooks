<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use EoneoPay\Utils\XmlConverter;
use EoneoPay\Webhooks\Exceptions\JsonSerialisationException;
use EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestBuilderInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Zend\Diactoros\Request;

class RequestBuilder implements RequestBuilderInterface
{
    /**
     * @var \Psr\Http\Message\StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * Constructor
     *
     * @param \Psr\Http\Message\StreamFactoryInterface $streamFactory
     */
    public function __construct(StreamFactoryInterface $streamFactory)
    {
        $this->streamFactory = $streamFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidXmlTagException
     * @throws \EoneoPay\Webhooks\Exceptions\JsonSerialisationException
     * @throws \EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException
     */
    public function build(WebhookRequestInterface $webhookRequest): RequestInterface
    {
        $body = $webhookRequest->getActivity()->getPayload();
        $headers = $webhookRequest->getRequestHeaders();
        $method = $webhookRequest->getRequestMethod();
        $uri = $webhookRequest->getRequestUrl();

        switch ($webhookRequest->getRequestFormat()) {
            case 'json': // @codeCoverageIgnore
                $json = \json_encode($body);
                if ($json === false) {
                    // @codeCoverageIgnoreStart
                    // This isnt going to happen in real life.
                    throw new JsonSerialisationException(\sprintf(
                        'An exception occurred trying to encode to JSON: %s',
                        \json_last_error_msg()
                    ));
                    // @codeCoverageIgnoreEnd
                }

                return new Request(
                    $uri,
                    $method,
                    $this->streamFactory->createStream($json),
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
