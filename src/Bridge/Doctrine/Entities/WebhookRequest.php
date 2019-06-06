<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entities;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Externals\ORM\Entity;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Schemas\WebhookRequestSchema;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\UnexpectedObjectException;
use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

/**
 * @ORM\Entity()
 * @ORM\Table("event_activity_requests")
 */
final class WebhookRequest extends Entity implements WebhookRequestInterface
{
    use WebhookRequestSchema {
        // Import the public method populate as a private method traitPopulate
        // so we can redefine, but still call the trait method.
        populate as private traitPopulate;
    }

    /**
     * @ORM\ManyToOne(targetEntity="EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity")
     *
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity
     */
    protected $activity;

    // @codeCoverageIgnoreStart
    /**
     * The WebhookRequest entity in this package is not intended to be created
     * manually. Use the WebhookPersister to create new WebhookRequest objects.
     *
     * @noinspection MagicMethodsValidityInspection PhpMissingParentConstructorInspection
     */
    private function __construct()
    {
    }
    // @codeCoverageIgnoreEnd

    /**
     * {@inheritdoc}
     */
    public function getActivity(): ActivityInterface
    {
        return $this->activity;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestFormat(): string
    {
        return $this->requestFormat;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestHeaders(): array
    {
        return $this->requestHeaders;
    }

    /**
     * Returns the request id
     *
     * @return int
     */
    public function getRequestId(): int
    {
        // Cast requestId to int as doctrine hydrates bigint as a string.

        return (int) $this->requestId;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getSequence(): ?int
    {
        return $this->getRequestId();
    }

    /**
     * {@inheritdoc}
     */
    public function populate(ActivityInterface $activity, SubscriptionInterface $subscription): void
    {
        $this->setActivity($activity);
        $this->traitPopulate($activity, $subscription);
    }

    /**
     * Associates the activity.
     *
     * @param \EoneoPay\Webhooks\Model\ActivityInterface $activity
     *
     * @return void
     */
    public function setActivity(ActivityInterface $activity): void
    {
        if ($activity instanceof Activity === false) {
            throw new UnexpectedObjectException(\sprintf(
                'The %s class expects a %s for the %s property, got %s',
                __CLASS__,
                Activity::class,
                'activity',
                \get_class($activity)
            ));
        }

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity $activity
         *
         * @see https://youtrack.jetbrains.com/issue/WI-37859 - typehint required until PhpStorm recognises === check
         */

        // We're not able to call associate because it relies on being able
        // to call getActivity, which wont work because we have a null state.
        $this->activity = $activity;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'activity' => $this->activity->toArray(),
            'id' => $this->getRequestId(),
            'request_format' => $this->getRequestFormat(),
            'request_headers' => $this->getRequestHeaders(),
            'request_method' => $this->getRequestMethod(),
            'request_url' => $this->getRequestUrl()
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getIdProperty(): string
    {
        return 'requestId';
    }
}
