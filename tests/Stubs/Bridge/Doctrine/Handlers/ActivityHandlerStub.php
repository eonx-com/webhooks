<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\ActivityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;

class ActivityHandlerStub implements ActivityHandlerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\ActivityInterface[]
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
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entity\ActivityInterface[]
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
