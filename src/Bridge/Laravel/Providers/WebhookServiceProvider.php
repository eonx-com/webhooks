<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Providers;

use EoneoPay\Externals\Bridge\Laravel\EventDispatcher as RealEventDispatcher;
use EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface as RealEventDispatcherInterface;
use EoneoPay\Externals\Logger\Interfaces\LoggerInterface;
use EoneoPay\Webhooks\Activity\ActivityManager;
use EoneoPay\Webhooks\Activity\Interfaces\ActivityManagerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ActivityHandler;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\RequestHandler;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler;
use EoneoPay\Webhooks\Bridge\Doctrine\Persister\ActivityPersister;
use EoneoPay\Webhooks\Bridge\Doctrine\Persister\WebhookPersister;
use EoneoPay\Webhooks\Bridge\Laravel\Events\EventDispatcher;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Events\LoggerAwareEventDispatcher;
use EoneoPay\Webhooks\Payload\Interfaces\PayloadManagerInterface;
use EoneoPay\Webhooks\Payload\PayloadManager;
use EoneoPay\Webhooks\Persister\Interfaces\ActivityPersisterInterface;
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
        $this->app->singleton(ActivityHandlerInterface::class, ActivityHandler::class);
        $this->app->singleton(ActivityManagerInterface::class, ActivityManager::class);
        $this->app->singleton(ActivityPersisterInterface::class, ActivityPersister::class);
        $this->app->singleton(
            EventDispatcherInterface::class,
            static function (Container $app): EventDispatcherInterface {
                $dispatcher = $app->make(EventDispatcher::class);

                return new LoggerAwareEventDispatcher(
                    $dispatcher,
                    $app->make(LoggerInterface::class)
                );
            }
        );
        $this->app->singleton(PayloadManagerInterface::class, static function (Container $app): PayloadManager {
            $tagged = $app->tagged('webhooks_payload_builders');

            return new PayloadManager($tagged);
        });
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
        $this->app->singleton(RealEventDispatcherInterface::class, RealEventDispatcher::class);
        $this->app->singleton(WebhookPersisterInterface::class, WebhookPersister::class);
    }
}
