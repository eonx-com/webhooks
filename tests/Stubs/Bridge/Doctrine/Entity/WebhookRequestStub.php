<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity;

use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;
use Illuminate\Support\Collection;
use RuntimeException;

class WebhookRequestStub implements WebhookRequestInterface
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private $data;

    /**
     * WebhookEntityStub constructor.
     *
     * @param null|int $sequence
     */
    public function __construct(?int $sequence)
    {
        $this->data = \collect([
            'sequence' => $sequence
        ]);
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
     * Returns data
     *
     * @return \Illuminate\Support\Collection
     */
    public function getData(): Collection
    {
        return $this->data;
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
}
