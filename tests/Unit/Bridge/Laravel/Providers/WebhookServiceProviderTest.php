<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Providers;

use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface as RealEventDispatcher;
use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface as HttpClientInterface;
use EoneoPay\Externals\Logger\Interfaces\LoggerInterface;
use EoneoPay\Externals\Logger\Logger;
use EoneoPay\Utils\Interfaces\XmlConverterInterface;
use EoneoPay\Utils\XmlConverter;
use EoneoPay\Webhooks\Activities\Interfaces\ActivityFactoryInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\ActivityCreatedListener;
use EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookServiceProvider;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionResolverInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestBuilderInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestFactoryInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface;
use Illuminate\Container\Container;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\Externals\HttpClientStub;
use Tests\EoneoPay\Webhooks\Stubs\Payload\PayloadBuilderStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionResolverStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\Common\Persistence\ManagerRegistryStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\EntityManagerStub;
use Tests\EoneoPay\Webhooks\WebhookTestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookServiceProvider
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) Test case only, high coupling required to fully test service provider
 */
class WebhookServiceProviderTest extends WebhookTestCase
{
    /**
     * @var \Illuminate\Container\Container|null
     */
    private $app;

    /**
     * Returns interfaces that should be registered in the
     * container.
     *
     * @return string[][]
     */
    public function getRegisteredInterfaces(): array
    {
        return [
            [ActivityCreatedListener::class],
            [ActivityFactoryInterface::class],
            [EventDispatcherInterface::class],
            [RealEventDispatcher::class],
            [RequestHandlerInterface::class],
            [ResponseHandlerInterface::class],
            [RequestBuilderInterface::class],
            [RequestFactoryInterface::class],
            [RequestProcessorInterface::class],
            [WebhookPersisterInterface::class]
        ];
    }

    /**
     * Test provider register container.
     *
     * @param string $interface
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     *
     * @dataProvider getRegisteredInterfaces
     */
    public function testRegister(string $interface): void
    {
        $app = $this->getApplication();

        self::assertInstanceOf($interface, $app->get($interface));
    }

    /**
     * Get application instance
     *
     * @return \Illuminate\Container\Container
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getApplication(): Container
    {
        // If app already exists, return
        if (($this->app instanceof Container) === true) {
            return $this->app;
        }

        $app = $this->createApplication();
        $app->bind(SubscriptionResolverInterface::class, SubscriptionResolverStub::class);
        $app->bind(RealEventDispatcher::class, EventDispatcherStub::class);
        $app->bind(XmlConverterInterface::class, XmlConverter::class);
        $app->bind(HttpClientInterface::class, HttpClientStub::class);
        $app->bind(LoggerInterface::class, Logger::class);

        $app->instance('payload_builder_real', new PayloadBuilderStub([]));
        $app->instance('payload_builder_unreal', new class
        {
        });
        $app->tag(['payload_builder_real', 'payload_builder_unreal'], ['webhooks_payload_builders']);

        $app->bind(EntityManagerInterface::class, EntityManagerStub::class);
        $app->bind('registry', ManagerRegistryStub::class);

        /** @noinspection PhpParamsInspection Lumen application is a foundation application */
        $provider = new WebhookServiceProvider($app);
        $provider->register();

        return $this->app = $app;
    }
}
