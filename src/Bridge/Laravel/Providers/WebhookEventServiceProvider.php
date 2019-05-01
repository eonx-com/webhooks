<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Providers;

use EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use Laravel\Lumen\Providers\EventServiceProvider;

class WebhookEventServiceProvider extends EventServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function __construct($app)
    {
        parent::__construct($app);

        // Set listeners
        $this->listen = [
            EventInterface::class => [
                WebhookEventListener::class
            ]
        ];
    }
}
