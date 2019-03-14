<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Providers;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
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
use Tests\EoneoPay\Webhooks\Stubs\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\HttpClientStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionRetrieverStub;
use Tests\EoneoPay\Webhooks\WebhookTestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookServiceProvider
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) Test case only, high coupling required to fully test service provider
 */
class WebhookServiceProviderTest extends WebhookTestCase
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookServiceProvider
     */
    private $provider;

    /**
     * Returns interfaces that should be registered in the
     * container.
     *
     * @return string[][]
     */
    public function getRegisteredInterfaces(): array
    {
        return [
            [ClientInterface::class],
            [EventCreatorInterface::class],
            [EventDispatcherInterface::class],
            [WebhookEventDispatcherInterface::class],
            [WebhookEventListener::class],
            [WebhookInterface::class],
            [ResponseHandlerInterface::class],
            [WebhookPersisterInterface::class]
        ];
    }

    /**
     * Test provider register container.
     *
     * @dataProvider getRegisteredInterfaces
     *
     * @param string $interface
     *
     * @return void
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testRegister(string $interface): void
    {
        $app = $this->getApplication();

        self::assertInstanceOf(
            $interface,
            $app->get($interface)
        );
    }

    /**
     * Test case Setup
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $app = $this->getApplication();
        $app->bind(SubscriptionRetrieverInterface::class, SubscriptionRetrieverStub::class);
        $app->bind(EventDispatcherInterface::class, EventDispatcherStub::class);
        $app->bind(XmlConverterInterface::class, XmlConverter::class);
        $app->bind(HttpClientInterface::class, HttpClientStub::class);
        $app->bind(LoggerInterface::class, Logger::class);

        $doctrine = $this->createMock(EntityManagerInterface::class);
        $registry = $this->createMock(ManagerRegistry::class);
        $registry->method('getManager')
            ->willReturn($doctrine);
        $app->instance('registry', $registry);

        $this->provider = new WebhookServiceProvider($this->getApplication());
        $this->provider->register();
    }
}
