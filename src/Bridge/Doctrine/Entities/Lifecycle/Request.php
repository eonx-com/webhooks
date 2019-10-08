<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entities\Lifecycle;

use DateTime as BaseDateTime;
use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Utils\Interfaces\SerializableInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Entity;
use EoneoPay\Webhooks\Bridge\Doctrine\Schemas\Lifecycle\RequestSchema;
use EoneoPay\Webhooks\Models\ActivityInterface;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;
use EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface;

/**
 * @ORM\Entity(repositoryClass="\EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Lifecycle\RequestRepository")
 * @ORM\Table(
 *     name="event_activity_requests",
 *     indexes={@ORM\Index(name="idx_created_at_webhook_request", columns={"created_at"})}
 * )
 */
class Request extends Entity implements WebhookRequestInterface
{
    use RequestSchema {
        // Import the public method populate as a private method traitPopulate
        // so we can redefine, but still call the trait method.
        populate as private traitPopulate;
    }

    /**
     * @ORM\ManyToOne(targetEntity="\EoneoPay\Webhooks\Models\ActivityInterface")
     *
     * @var \EoneoPay\Webhooks\Models\ActivityInterface
     */
    protected $activity;

    /**
     * The WebhookRequest entity in this package is not intended to be created
     * manually. Use the WebhookPersister to create new WebhookRequest objects.
     *
     * @noinspection MagicMethodsValidityInspection PhpMissingParentConstructorInspection
     *
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

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
    public function getCreatedAt(): ?BaseDateTime
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalId(): string
    {
        return (string)$this->getRequestId();
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
     * Returns the request id.
     *
     * @return int
     */
    public function getRequestId(): int
    {
        // Cast requestId to int as doctrine hydrates bigint as a string.

        return (int)$this->requestId;
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
     * @param \EoneoPay\Webhooks\Models\ActivityInterface $activity
     *
     * @return void
     */
    public function setActivity(ActivityInterface $activity): void
    {
        // We're not able to call associate because it relies on being able
        // to call getActivity, which wont work because we have a null state.
        $this->activity = $activity;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(BaseDateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        /**
         * @var \EoneoPay\Utils\Interfaces\SerializableInterface|null $activity
         *
         * @see https://youtrack.jetbrains.com/issue/WI-37859 - typehint required until PhpStorm recognises === check
         */
        $activity = $this->getActivity();

        return [
            'activity' => ($activity instanceof SerializableInterface) === true ? $activity->toArray() : null,
            'created_at' => $this->formatDate($this->getCreatedAt()),
            'id' => $this->getRequestId(),
            'request_format' => $this->getRequestFormat(),
            'request_headers' => $this->getRequestHeaders(),
            'request_method' => $this->getRequestMethod(),
            'request_url' => $this->getRequestUrl(),
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
