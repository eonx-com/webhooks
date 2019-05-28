<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces;

use EoneoPay\Webhooks\Model\ActivityInterface;

interface ActivityHandlerInterface
{
    /**
     * Creates a new real instance of ActivityInterface
     *
     * @return \EoneoPay\Webhooks\Model\ActivityInterface
     */
    public function create(): ActivityInterface;

    /**
     * Saves the webhook
     *
     * @param \EoneoPay\Webhooks\Model\ActivityInterface $activity
     *
     * @return void
     */
    public function save(ActivityInterface $activity): void;
}
