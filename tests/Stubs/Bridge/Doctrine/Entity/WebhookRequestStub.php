<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;
use Illuminate\Support\Collection;

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
    public function populate(string $event, array $payload, SubscriptionInterface $subscription): void
    {
        $this->data['event'] = $event;
        $this->data['payload'] = $payload;
        $this->data['format'] = $subscription->getSerializationFormat();
        $this->data['headers'] = $subscription->getHeaders();
        $this->data['method'] = $subscription->getMethod();
        $this->data['url'] = $subscription->getUrl();
    }
}
