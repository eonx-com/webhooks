<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Events;

use EoneoPay\Utils\Interfaces\XmlConverterInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventCreatorInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

final class EventCreator implements EventCreatorInterface
{
    /**
     * @var \EoneoPay\Utils\Interfaces\XmlConverterInterface
     */
    private $xmlConverter;

    /**
     * Create the creator
     *
     * @param \EoneoPay\Utils\Interfaces\XmlConverterInterface $xmlConverter
     */
    public function __construct(XmlConverterInterface $xmlConverter)
    {
        $this->xmlConverter = $xmlConverter;
    }

    /**
     * @inheritdoc
     */
    public function create(
        string $event,
        int $sequence,
        array $payload,
        SubscriptionInterface $subscription
    ): EventInterface {
        $serializedPayload = $this->serialisePayload(
            \compact('sequence', 'payload'),
            $subscription->getSerializationFormat()
        );

        return new Event(
            $subscription->getUrl(),
            $subscription->getMethod(),
            $serializedPayload,
            $subscription->getHeaders()
        );
    }

    /**
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
