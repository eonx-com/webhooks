<?php declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Providers;

use EoneoPay\Webhook\Bridge\Laravel\Events\WebhookHttpEvent;
use EoneoPay\Webhook\Bridge\Laravel\Events\WebhookSlackEvent;
use EoneoPay\Webhook\Bridge\Laravel\Listeners\WebhookEventListener;
use Laravel\Lumen\Providers\EventServiceProvider;

class WebhookEventServiceProvider extends EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        WebhookSlackEvent::class => [
            WebhookEventListener::class
        ],
        WebhookHttpEvent::class => [
            WebhookEventListener::class
        ]
    ];
}
