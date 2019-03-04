<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Providers;

use EoneoPay\Webhooks\Bridge\Laravel\Events\EventCreator;
use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookEventDispatcher;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener;
use EoneoPay\Webhooks\Events\Interfaces\EventCreatorInterface;
use EoneoPay\Webhooks\Events\Interfaces\WebhookEventDispatcherInterface;
use EoneoPay\Webhooks\Webhook\Interfaces\WebhookInterface;
use EoneoPay\Webhooks\Webhook\Webhook;
use Illuminate\Support\ServiceProvider;

class WebhookServiceProvider extends ServiceProvider
{
    /**
     * Register event dispatcher into Laravel application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(WebhookInterface::class, Webhook::class);

        $this->app->singleton(EventCreatorInterface::class, EventCreator::class);

        $this->app->singleton(WebhookEventDispatcherInterface::class, WebhookEventDispatcher::class);

        $this->app->singleton(WebhookEventListener::class);
    }
}
