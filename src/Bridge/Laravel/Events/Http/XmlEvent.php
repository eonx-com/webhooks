<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Events\Http;

use EoneoPay\Utils\Arr;
use EoneoPay\Webhook\Bridge\Laravel\Events\Event;
use EoneoPay\Webhook\Events\Interfaces\Http\XmlEventInterface;

class XmlEvent extends Event implements XmlEventInterface
{
    /**
     * XmlEvent constructor.
     *
     * @param null|string $url
     * @param null|string $method
     * @param array|null $payload
     * @param array|null $headers
     * @param null|string $rootNode
     */
    public function __construct(
        ?string $url = null,
        ?string $method = null,
        ?array $payload = [],
        ?array $headers = [],
        ?string $rootNode = null
    ) {
        // merge json headers
        $headers = $this->mergeHeaders($headers);

        // call parent constructor
        parent::__construct($url, $method, $payload, $headers);

        $this->setRootNode($rootNode);
    }

    /**
     * Set xml root node.
     *
     * @param null|string $rootNode Xml root node
     *
     * @return \EoneoPay\Webhook\Events\Interfaces\Http\XmlEventInterface
     */
    public function setRootNode(?string $rootNode = null): XmlEventInterface
    {
        $this->collection->set('root_node', $rootNode);

        return $this;
    }

    /**
     * Serialize xml event.
     *
     * @return array
     */
    public function serialize(): array
    {
        return [
            'headers' => $this->collection->get('headers')->toArray() ?? [],
            'body' => $this->collection->get('payload')->toXml($this->collection->get('root_node'))
        ];
    }

    /**
     * Merge xml headers.
     *
     * @param array|null $headers
     *
     * @return array
     */
    private function mergeHeaders(?array $headers = []): array
    {
        $headers = $headers ?? [];

        $arr = new Arr();

        return $arr->merge($headers, [
            'Content-Type' => 'application/xml',
            'Accept' => 'application/xml'
        ]);
    }
}
