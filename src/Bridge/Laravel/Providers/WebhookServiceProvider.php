<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Providers;

use EoneoPay\Externals\Bridge\Laravel\EventDispatcher;
use EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface;
use EoneoPay\Externals\Logger\Interfaces\LoggerInterface;
use EoneoPay\Webhooks\Activity\ActivityManager;
use EoneoPay\Webhooks\Activity\Interfaces\ActivityManagerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\RequestHandler;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler;
use EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister;
use EoneoPay\Webhooks\Bridge\Laravel\Events\EventCreator;
use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookEventDispatcher;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener;
use EoneoPay\Webhooks\Client\Client;
use EoneoPay\Webhooks\Client\Interfaces\ClientInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventCreatorInterface;
use EoneoPay\Webhooks\Events\Interfaces\WebhookEventDispatcherInterface;
use EoneoPay\Webhooks\Events\LoggerAwareEventDispatcher;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

/**
 * Class WebhookServiceProvider
 *
 * @package EoneoPay\Webhooks\Bridge\Laravel\Providers
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) ServiceProvider coupling because of bindings
 */
class WebhookServiceProvider extends ServiceProvider
{
    /**
     * @noinspection PhpMissingParentCallCommonInspection Parent implementation is empty
     *
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->singleton(ActivityManagerInterface::class, ActivityManager::class);
        $this->app->singleton(ClientInterface::class, Client::class);
        $this->app->singleton(EventCreatorInterface::class, EventCreator::class);
        $this->app->singleton(EventDispatcherInterface::class, EventDispatcher::class);
        $this->app->singleton(
            RequestHandlerInterface::class,
            static function (Container $app): RequestHandlerInterface {
                return new RequestHandler($app->make('registry')->getManager());
            }
        );
        $this->app->singleton(
            ResponseHandlerInterface::class,
            static function (Container $app): ResponseHandlerInterface {
                return new ResponseHandler($app->make('registry')->getManager());
            }
        );
        $this->app->singleton(
            WebhookEventDispatcherInterface::class,
            static function (Container $app): WebhookEventDispatcherInterface {
                $dispatcher = new WebhookEventDispatcher($app->make(EventDispatcherInterface::class));

                return new LoggerAwareEventDispatcher(
                    $dispatcher,
                    $app->make(LoggerInterface::class)
                );
            }
        );
        $this->app->singleton(WebhookEventListener::class);
        $this->app->singleton(WebhookPersisterInterface::class, WebhookPersister::class);
    }
}
