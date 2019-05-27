<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\ActivityInterface;

interface ActivityHandlerInterface
{
    /**
     * Creates a new real instance of ActivityInterface
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entity\ActivityInterface
     */
    public function create(): ActivityInterface;

    /**
     * Saves the webhook
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\ActivityInterface $activity
     *
     * @return void
     */
    public function save(ActivityInterface $activity): void;
}
