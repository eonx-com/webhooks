<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface;
use EoneoPay\Webhooks\Models\ActivityInterface;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\ActivityStub;

/**
 * @coversNothing
 */
class ActivityHandlerStub implements ActivityHandlerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Models\ActivityInterface|null
     */
    private $next;

    /**
     * @var \EoneoPay\Webhooks\Models\ActivityInterface[]
     */
    private $saved = [];

    /**
     * {@inheritdoc}
     */
    public function create(): ActivityInterface
    {
        return new ActivityStub();
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $activityId): ?ActivityInterface
    {
        return $this->next;
    }

    /**
     * Returns saved activities.
     *
     * @return \EoneoPay\Webhooks\Models\ActivityInterface[]
     */
    public function getSaved(): array
    {
        return $this->saved;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ActivityInterface $activity): void
    {
        $this->saved[] = $activity;
    }

    /**
     * Set next.
     *
     * @param \EoneoPay\Webhooks\Models\ActivityInterface $activity
     *
     * @return void
     */
    public function setNext(ActivityInterface $activity): void
    {
        $this->next = $activity;
    }
}
