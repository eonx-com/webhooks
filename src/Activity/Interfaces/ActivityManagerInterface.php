<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Activity\Interfaces;

use EoneoPay\Webhooks\Activity\Interfaces\ActivityDataInterface;

interface ActivityManagerInterface
{
    /**
     * The entry point for sending webhooks. This method will dispatch jobs
     * for hitting all webhook subscriptions that match the event and its
     * subscribers.
     *
     * @param \EoneoPay\Webhooks\Activity\Interfaces\ActivityDataInterface $webhookData
     *
     * @return void
     */
    public function send(ActivityDataInterface $webhookData): void;
}
