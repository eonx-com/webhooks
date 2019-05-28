<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\WebhookManagerInterface;

class WebhookManager implements WebhookManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function processActivity(ActivityInterface $activity): void
    {
    }
}
