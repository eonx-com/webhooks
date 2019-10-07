<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Providers;

use EoneoPay\Webhooks\Bridge\Laravel\Events\ActivityCreatedEvent;
use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestCreatedEvent;
use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestRetryEvent;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\ActivityCreatedListener;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\RequestCreatedListener;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\RequestRetryListener;
use Laravel\Lumen\Providers\EventServiceProvider;

final class WebhookEventServiceProvider extends EventServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function __construct($app)
    {
        parent::__construct($app);

        // Set listeners
        $this->listen = [
            ActivityCreatedEvent::class => [
                ActivityCreatedListener::class,
            ],
            WebhookRequestCreatedEvent::class => [
                RequestCreatedListener::class,
            ],
            WebhookRequestRetryEvent::class => [
                RequestRetryListener::class,
            ],
        ];
    }
}
