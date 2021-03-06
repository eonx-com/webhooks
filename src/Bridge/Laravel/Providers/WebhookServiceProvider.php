<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Providers;

use EoneoPay\Externals\Bridge\Laravel\EventDispatcher as RealEventDispatcher;
use EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface as RealEventDispatcherInterface;
use EoneoPay\Externals\Logger\Interfaces\LoggerInterface;
use EoneoPay\Webhooks\Activities\ActivityFactory;
use EoneoPay\Webhooks\Activities\Interfaces\ActivityFactoryInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ActivityHandler;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\RequestHandler;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\ResponseHandler;
use EoneoPay\Webhooks\Bridge\Doctrine\Persisters\ActivityPersister;
use EoneoPay\Webhooks\Bridge\Doctrine\Persisters\WebhookPersister;
use EoneoPay\Webhooks\Bridge\Laravel\Events\EventDispatcher;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\ActivityCreatedListener;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Events\LoggerAwareEventDispatcher;
use EoneoPay\Webhooks\Payloads\Interfaces\PayloadBuilderInterface;
use EoneoPay\Webhooks\Payloads\Interfaces\PayloadManagerInterface;
use EoneoPay\Webhooks\Payloads\PayloadManager;
use EoneoPay\Webhooks\Persisters\Interfaces\ActivityPersisterInterface;
use EoneoPay\Webhooks\Persisters\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestBuilderInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestFactoryInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RetryProcessorInterface;
use EoneoPay\Webhooks\Webhooks\RequestBuilder;
use EoneoPay\Webhooks\Webhooks\RequestFactory;
use EoneoPay\Webhooks\Webhooks\RequestProcessor;
use EoneoPay\Webhooks\Webhooks\RetryProcessor;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Zend\Diactoros\StreamFactory;

/**
 * Class WebhookServiceProvider.
 *
 * @package EoneoPay\Webhooks\Bridge\Laravel\Providers
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) ServiceProvider coupling because of bindings
 */
final class WebhookServiceProvider extends ServiceProvider
{
    /**
     * @noinspection PhpMissingParentCallCommonInspection Parent implementation is empty
     *
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->singleton(ActivityCreatedListener::class);
        $this->app->singleton(ActivityFactoryInterface::class, ActivityFactory::class);
        $this->app->singleton(ActivityHandlerInterface::class, static function (Container $app): ActivityHandler {
            $entityManager = $app->make('registry')->getManager();

            return new ActivityHandler($entityManager);
        });
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
            $payloadBuilders = [];
            foreach ($tagged as $service) {
                if ($service instanceof PayloadBuilderInterface !== true) {
                    continue;
                }

                $payloadBuilders[] = $service;
            }

            return new PayloadManager($payloadBuilders);
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
        $this->app->singleton(RequestBuilderInterface::class, static function (): RequestBuilder {
            return new RequestBuilder(new StreamFactory());
        });
        $this->app->singleton(RequestFactoryInterface::class, RequestFactory::class);
        $this->app->singleton(RequestProcessorInterface::class, RequestProcessor::class);
        $this->app->singleton(RetryProcessorInterface::class, RetryProcessor::class);
        $this->app->singleton(WebhookPersisterInterface::class, WebhookPersister::class);
    }
}
