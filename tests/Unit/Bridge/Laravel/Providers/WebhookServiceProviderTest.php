<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Providers;

use EoneoPay\Externals\EventDispatcher\Interfaces\EventDispatcherInterface;
use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface as HttpClientInterface;
use EoneoPay\Externals\Logger\Interfaces\LoggerInterface;
use EoneoPay\Externals\Logger\Logger;
use EoneoPay\Utils\Interfaces\XmlConverterInterface;
use EoneoPay\Utils\XmlConverter;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener;
use EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookServiceProvider;
use EoneoPay\Webhooks\Client\Interfaces\ClientInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventCreatorInterface;
use EoneoPay\Webhooks\Events\Interfaces\WebhookEventDispatcherInterface;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionRetrieverInterface;
use EoneoPay\Webhooks\Webhook\Interfaces\WebhookInterface;
use Illuminate\Container\Container;
use Tests\EoneoPay\Webhooks\Stubs\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\HttpClientStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionRetrieverStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\Common\Persistence\ManagerRegistryStub;
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
            'ClientInterface' => [ClientInterface::class],
            'EventCreatorInterface' => [EventCreatorInterface::class],
            'EventDispatcherInterface' => [EventDispatcherInterface::class],
            'WebhookEventDispatcherInterface' => [WebhookEventDispatcherInterface::class],
            'WebhookEventListener' => [WebhookEventListener::class],
            'WebhookInterface' => [WebhookInterface::class],
            'ResponseHandlerInterface' => [ResponseHandlerInterface::class],
            'WebhookPersisterInterface' => [WebhookPersisterInterface::class]
        ];
    }

    /**
     * Test provider register container.
     *
     * @param string $interface
     *
     * @return void
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
     */
    private function getApplication(): Container
    {
        // If app already exists, return
        if (($this->app instanceof Container) === true) {
            return $this->app;
        }

        $app = $this->createApplication();
        $app->bind(SubscriptionRetrieverInterface::class, SubscriptionRetrieverStub::class);
        $app->bind(EventDispatcherInterface::class, EventDispatcherStub::class);
        $app->bind(XmlConverterInterface::class, XmlConverter::class);
        $app->bind(HttpClientInterface::class, HttpClientStub::class);
        $app->bind(LoggerInterface::class, Logger::class);

        $app->bind('registry', ManagerRegistryStub::class);

        /** @noinspection PhpParamsInspection Lumen application is a foundation application */
        $provider = new WebhookServiceProvider($app);
        $provider->register();

        return $this->app = $app;
    }
}
