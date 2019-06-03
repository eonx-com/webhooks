<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Client;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface as HttpClientInterface;
use EoneoPay\Utils\Interfaces\XmlConverterInterface;
use EoneoPay\Webhooks\Client\Interfaces\ClientInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;

class Client implements ClientInterface
{
    /**
     * @var \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface
     */
    private $httpClient;

    /**
     * @var \EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface
     */
    private $webhookPersister;

    /**
     * @var \EoneoPay\Utils\Interfaces\XmlConverterInterface
     */
    private $xmlConverter;

    /**
     * Constructor.
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface $httpClient
     * @param \EoneoPay\Utils\Interfaces\XmlConverterInterface $xmlConverter
     * @param \EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface $webhookPersister
     */
    public function __construct(
        HttpClientInterface $httpClient,
        XmlConverterInterface $xmlConverter,
        WebhookPersisterInterface $webhookPersister
    ) {
        $this->httpClient = $httpClient;
        $this->xmlConverter = $xmlConverter;
        $this->webhookPersister = $webhookPersister;
    }

    /**
     * {@inheritdoc}
     */
    public function send(EventInterface $event): void
    {
        $payload = $this->buildPayload($event);

        $serialisedPayload = $this->serialisePayload($payload, $event->getFormat());

        $response = $this->httpClient->request(
            $event->getMethod(),
            $event->getUrl(),
            [
                'body' => $serialisedPayload,
                'headers' => $this->buildHeaders($event)
            ]
        );

        $this->webhookPersister->update($event->getSequence(), $response);
    }

    /**
     * Builds an array of headers including content type
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     *
     * @return string[]
     */
    private function buildHeaders(EventInterface $event): array
    {
        $headers = $event->getHeaders();

        if (\array_key_exists('Content-Type', $headers) === false) {
            $headers['Content-Type'] = $this->getContentType($event->getFormat());
        }

        return $headers;
    }

    /**
     * Prepares the final payload to be sent.
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     *
     * @return mixed[]
     */
    private function buildPayload(EventInterface $event): array
    {
        $finalPayload = $event->getPayload();
        $finalPayload['_sequence'] = $event->getSequence();
        $finalPayload['_event'] = $event->getEvent();

        return $finalPayload;
    }

    /**
     * Returns a content type for the specific format.
     *
     * @param string $format
     *
     * @return string
     */
    private function getContentType(string $format): string
    {
        switch (true) {
            case $format === 'json':
                return 'application/json';

            case $format === 'xml':
                return 'application/xml';

            // @codeCoverageIgnoreStart
            // This will never occur and is present for safety: serialisePayload
            // will throw before we get here.
            default:
                throw new UnknownSerialisationFormatException($format);
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Serialises the payload to the requested format.
     *
     * @param mixed[] $payload
     * @param string $format
     *
     * @return string
     */
    private function serialisePayload(array $payload, string $format): ?string
    {
        switch (true) {
            case $format === 'json':
                return \json_encode($payload) ?: '';

            case $format === 'xml':
                return $this->xmlConverter->arrayToXml($payload, 'webhook');

            default:
                throw new UnknownSerialisationFormatException($format);
        }
    }
}
