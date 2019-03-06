<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Persister;

use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

class WebhookPersisterStub implements WebhookPersisterInterface
{
    /**
     * @var int
     */
    private $nextSequence = 1;

    /**
     * @var mixed[]
     */
    private $saved = [];

    /**
     * @return mixed[]
     */
    public function getSaved(): array
    {
        return $this->saved;
    }

    /**
     * @inheritdoc
     */
    public function save(string $event, array $payload, SubscriptionInterface $subscription): int
    {
        $this->saved[] = \compact('event', 'payload', 'subscription');

        return $this->nextSequence;
    }

    /**
     * Sets the next sequence
     *
     * @param int $seq
     *
     * @return void
     */
    public function setNextSequence(int $seq): void
    {
        $this->nextSequence = $seq;
    }
}
