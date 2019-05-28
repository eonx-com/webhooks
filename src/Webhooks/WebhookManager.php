<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\WebhookManagerInterface;

class WebhookManager implements WebhookManagerInterface
{
    /**
     *
     * @param ActivityInterface $activity
     *
     * @return void
     */
    public function processActivity(ActivityInterface $activity): void
    {

    }
}
