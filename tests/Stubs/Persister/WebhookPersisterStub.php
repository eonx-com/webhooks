<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Persister;

use EoneoPay\Webhooks\Models\ActivityInterface;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;
use EoneoPay\Webhooks\Persisters\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscriptions\Interfaces\SubscriptionInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * @coversNothing
 */
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
    public function saveRequest(ActivityInterface $activity, SubscriptionInterface $subscription): int
    {
        $this->saved[] = \compact('activity', 'subscription');

        return $this->nextSequence;
    }

    /**
     * {@inheritdoc}
     */
    public function saveResponse(WebhookRequestInterface $webhookRequest, ResponseInterface $response): void
    {
        $this->updates[] = [
            'response' => $response,
            'sequence' => $webhookRequest->getSequence(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function saveResponseException(WebhookRequestInterface $webhookRequest, Throwable $exception)
    {
        $this->updates[] = [
            'exception' => $exception,
            'sequence' => $webhookRequest->getSequence(),
        ];
    }

    /**
     * Sets the next sequence.
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
