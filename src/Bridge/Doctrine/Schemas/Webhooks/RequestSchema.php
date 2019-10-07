<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Schemas\Webhooks;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Webhooks\Models\ActivityInterface;
use EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface;

/**
 * @method DateTime|null getCreatedAt()
 * @method string|null getRequestFormat()
 * @method mixed[] getRequestHeaders()
 * @method int|null getRequestId()
 * @method string|null getRequestMethod()
 * @method string|null getRequestUrl()
 * @method $this setCreatedAt(DateTime $createdAt)
 * @method $this setRequestFormat(string $format)
 * @method $this setRequestHeaders(array $headers)
 * @method $this setRequestId(int $id)
 * @method $this setRequestMethod(string $method)
 * @method $this setRequestUrl(string $url)
 */
trait RequestSchema
{
    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     *
     * @var \DateTime
     */
    protected $createdAt;

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
     * @ORM\Column(type="bigint", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Id()
     *
     * @var string
     */
    protected $requestId;

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
     * Populates a WebhookRequest with data.
     *
     * @param \EoneoPay\Webhooks\Models\ActivityInterface $activity
     * @param \EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface $subscription
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter) Required by interface
     */
    public function populate(ActivityInterface $activity, SubscriptionInterface $subscription): void
    {
        $this->requestFormat = $subscription->getSerializationFormat();
        $this->requestHeaders = $subscription->getHeaders();
        $this->requestMethod = $subscription->getMethod();
        $this->requestUrl = $subscription->getUrl();
    }
}
