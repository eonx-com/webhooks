<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Providers;

use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookEventDispatcher;
use EoneoPay\Webhooks\Bridge\Laravel\Jobs\WebhookJobDispatcher;
use EoneoPay\Webhooks\Events\Interfaces\WebhookEventDispatcherInterface;
use EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface;
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
        // bind interface to implementation
        $this->app->bind(WebhookEventDispatcherInterface::class, WebhookEventDispatcher::class);
        $this->app->bind(WebhookJobDispatcherInterface::class, WebhookJobDispatcher::class);
    }
}
