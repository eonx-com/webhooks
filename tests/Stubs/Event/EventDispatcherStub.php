<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Event;

use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;

/**
 * @coversNothing
 */
class EventDispatcherStub implements EventDispatcherInterface
{
    /**
     * @var int[]
     */
    private $activityCreated = [];

    /**
     * @var int[]
     */
    private $webhooksRequested = [];

    /**
     * @var int[]
     */
    private $webhooksRetried = [];

    /**
     * {@inheritdoc}
     */
    public function dispatchActivityCreated(int $activityId): void
    {
        $this->activityCreated[] = $activityId;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatchRequestCreated(int $requestId): void
    {
        $this->webhooksRequested[] = $requestId;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatchRequestRetry(int $requestId): void
    {
        $this->webhooksRetried[] = $requestId;
    }

    /**
     * Returns raised activities
     *
     * @return int[]
     */
    public function getActivityCreated(): array
    {
        return $this->activityCreated;
    }

    /**
     * Returns webhooks requested
     *
     * @return int[]
     */
    public function getWebhooksRequested(): array
    {
        return $this->webhooksRequested;
    }

    /**
     * Returns webhooks retried
     *
     * @return int[]
     */
    public function getWebhooksRetried(): array
    {
        return $this->webhooksRetried;
    }
}
