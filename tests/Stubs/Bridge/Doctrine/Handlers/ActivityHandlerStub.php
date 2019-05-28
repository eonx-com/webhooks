<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface;
use EoneoPay\Webhooks\Model\ActivityInterface;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;

class ActivityHandlerStub implements ActivityHandlerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Model\ActivityInterface[]
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
     * Returns saved activities.
     *
     * @return \EoneoPay\Webhooks\Model\ActivityInterface[]
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
}
