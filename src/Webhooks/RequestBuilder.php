<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use EoneoPay\Utils\XmlConverter;
use EoneoPay\Webhooks\Exceptions\InvalidRequestException;
use EoneoPay\Webhooks\Exceptions\JsonSerialisationException;
use EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;
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
     * Constructor.
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
        $this->validateRequest($webhookRequest);

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
                        'content-type' => 'application/json',
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
                        'content-type' => 'application/xml',
                    ])
                );
        }

        throw new UnknownSerialisationFormatException(\sprintf(
            'The "%s" format is unknown.',
            $webhookRequest->getRequestFormat()
        ));
    }

    /**
     * Validate the request object contains values that are compatible with the Diactoros request object
     *
     * @param \EoneoPay\Webhooks\Models\WebhookRequestInterface $webhookRequest
     *
     * @return void
     *
     * @throws \EoneoPay\Webhooks\Exceptions\InvalidRequestException
     */
    private function validateRequest(WebhookRequestInterface $webhookRequest): void
    {
        /**
         * Whilst this check should not be required due to type-hinting, due to magic setters on the model not enforcing
         * strict type-hinting, it is possible that this array may contain 'mixed' values
         */
        foreach ($webhookRequest->getRequestHeaders() as $header => $value) {
            /** @var mixed $value */
            if (\is_scalar($value) === false) {
                /**
                 * The type & value of the header & value could be *anything* & any type, being verbose about
                 * the structure & types would be complex to handle
                 */
                throw new InvalidRequestException('Request headers must be a scalar value');
            }
        }
    }
}
