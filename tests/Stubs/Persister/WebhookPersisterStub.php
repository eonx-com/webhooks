<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Persister;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
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
     * @var mixed[]
     */
    private $updates = [];

    /**
     * @return mixed[]
     */
    public function getSaved(): array
    {
        return $this->saved;
    }

    /**
     * @return mixed[]
     */
    public function getUpdates(): array
    {
        return $this->updates;
    }

    /**
     * {@inheritdoc}
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

    /**
     * {@inheritdoc}
     */
    public function update(int $sequence, ResponseInterface $response): void
    {
        $this->updates[] = \compact('sequence', 'response');
    }
}
