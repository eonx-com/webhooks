<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Providers;

use EoneoPay\External\HttpClient\Client;
use EoneoPay\External\HttpClient\Interfaces\ClientInterface;
use EoneoPay\External\Logger\Interfaces\LoggerInterface;
use EoneoPay\External\Logger\Logger;
use EoneoPay\Webhook\Bridge\Laravel\Events\WebhookEventDispatcher;
use EoneoPay\Webhook\Bridge\Laravel\Jobs\WebhookJobDispatcher;
use EoneoPay\Webhook\Events\Interfaces\WebhookEventDispatcherInterface;
use EoneoPay\Webhook\Jobs\Interfaces\WebhookJobDispatcherInterface;
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
        $this->app->singleton(LoggerInterface::class, Logger::class);
        $this->app->singleton(ClientInterface::class, Client::class);
        $this->app->bind(WebhookEventDispatcherInterface::class, WebhookEventDispatcher::class);
        $this->app->bind(WebhookJobDispatcherInterface::class, WebhookJobDispatcher::class);
    }
}
