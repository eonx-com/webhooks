<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Providers;

use EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use Laravel\Lumen\Providers\EventServiceProvider;

class WebhookEventServiceProvider extends EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var mixed[]
     */
    protected $listen = [
        EventInterface::class => [
            WebhookEventListener::class
        ]
    ];
}
