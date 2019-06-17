<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity;

use DateTime as BaseDateTime;
use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;
use Illuminate\Support\Collection;
use RuntimeException;

/**
 * @coversNothing
 */
class WebhookRequestStub implements WebhookRequestInterface
{
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $data;

    /**
     * WebhookEntityStub constructor.
     *
     * @param null|int $sequence
     * @param mixed[]|null $data
     */
    public function __construct(?int $sequence, ?array $data = null)
    {
        $this->data = \collect(\array_merge($data ?? [], [
            'sequence' => $sequence
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function getActivity(): ActivityInterface
    {
        if ($this->data->has('activity') === false) {
            throw new RuntimeException('Cannot getActivity without an activity set');
        }

        return $this->data['activity'];
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): ?BaseDateTime
    {
        return $this->createdAt;
    }

    /**
     * Returns data
     *
     * @return \Illuminate\Support\Collection
     */
    public function getData(): Collection
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestFormat(): string
    {
        return $this->data['format'] ?? 'json';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestHeaders(): array
    {
        return $this->data['headers'] ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestMethod(): string
    {
        return $this->data['method'] ?? 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestUrl(): string
    {
        return $this->data['url'] ?? 'http://localhost';
    }

    /**
     * Returns a sequence number
     *
     * @return int
     */
    public function getSequence(): ?int
    {
        $sequence = $this->data->get('sequence');

        return \is_int($sequence) ? $sequence : null;
    }

    /**
     * {@inheritdoc}
     */
    public function populate(ActivityInterface $activity, SubscriptionInterface $subscription): void
    {
        $this->data['activity'] = $activity;
        $this->data['format'] = $subscription->getSerializationFormat();
        $this->data['headers'] = $subscription->getHeaders();
        $this->data['method'] = $subscription->getMethod();
        $this->data['url'] = $subscription->getUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(BaseDateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
