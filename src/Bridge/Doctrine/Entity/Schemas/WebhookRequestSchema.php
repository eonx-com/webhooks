<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entity\Schemas;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

/**
 * @method string|null getActivityKey()
 * @method int|null getWebhookId()
 * @method string|null getRequestFormat()
 * @method mixed[] getRequestHeaders()
 * @method string|null getRequestMethod()
 * @method string|null getRequestUrl()
 * @method $this setActivityKey(string $activityKey)
 * @method $this setWebhookId(int $id)
 * @method $this setRequestFormat(string $format)
 * @method $this setRequestHeaders(array $headers)
 * @method $this setRequestMethod(string $method)
 * @method $this setRequestUrl(string $url)
 */
trait WebhookRequestSchema
{
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string|null
     */
    protected $activityKey;

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
     * Populates a WebhookRequest with data.
     *
     * @param \EoneoPay\Webhooks\Model\ActivityInterface $activity
     * @param \EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface $subscription
     *
     * @return void
     */
    public function populate(ActivityInterface $activity, SubscriptionInterface $subscription): void
    {
        $this->activityKey = $activity->getActivityKey();
        $this->requestFormat = $subscription->getSerializationFormat();
        $this->requestHeaders = $subscription->getHeaders();
        $this->requestMethod = $subscription->getMethod();
        $this->requestUrl = $subscription->getUrl();
    }
}