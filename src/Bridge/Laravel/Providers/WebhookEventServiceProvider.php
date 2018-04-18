<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Providers;

use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookEvent;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener;
use Laravel\Lumen\Providers\EventServiceProvider;

class WebhookEventServiceProvider extends EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        WebhookEvent::class => [
            WebhookEventListener::class
        ]
    ];
}
