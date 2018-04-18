<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Payloads;

use EoneoPay\Utils\Repository;
use EoneoPay\Webhooks\Payloads\Interfaces\WebhookXmlPayloadInterface;

class WebhookXmlPayload extends Repository implements WebhookXmlPayloadInterface
{
    /** @var null|string XML root node */
    private $rootNode;

    /**
     * WebhookXmlPayload constructor.
     *
     * @param array|null $data
     * @param null|string $rootNode
     */
    public function __construct(?array $data = null, ?string $rootNode = null)
    {
        parent::__construct($data);
        $this->rootNode = $rootNode;
    }

    /**
     * Serialize the payload.
     *
     * @return mixed|null|string
     */
    public function serialize()
    {
        return $this->toXml($this->rootNode);
    }
}
