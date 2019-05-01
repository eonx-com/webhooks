<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity\Schemas;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

/**
 * @method string|null getEvent()
 * @method mixed[]|null getPayload()
 * @method int|null getWebhookId()
 * @method string|null getRequestFormat()
 * @method mixed[] getRequestHeaders()
 * @method string|null getRequestMethod()
 * @method string|null getRequestUrl()
 * @method $this setEvent(string $event)
 * @method $this setPayload(array $payload)
 * @method $this setWebhookId(int $id)
 * @method $this setRequestFormat(string $format)
 * @method $this setRequestHeaders(array $headers)
 * @method $this setRequestMethod(string $method)
 * @method $this setRequestUrl(string $url)
 */
trait WebhookSchema
{
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string|null
     */
    protected $event;

    /**
     * @ORM\Column(type="json")
     *
     * @var mixed[]
     */
    protected $payload;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $requestFormat;

    /**
     * @ORM\Column(type="json")
     *
     * @var mixed[]
     */
    protected $requestHeaders = [];

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $requestMethod;

    /**
     * @ORM\Column(type="string", length=1024)
     *
     * @var string
     */
    protected $requestUrl;

    /**
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     *
     * @var int
     */
    protected $webhookId;

    /**
     * {@inheritdoc}
     */
    public function populate(string $event, array $payload, SubscriptionInterface $subscription): void
    {
        $this->event = $event;
        $this->payload = $payload;
        $this->requestFormat = $subscription->getSerializationFormat();
        $this->requestHeaders = $subscription->getHeaders();
        $this->requestMethod = $subscription->getMethod();
        $this->requestUrl = $subscription->getUrl();
    }
}
