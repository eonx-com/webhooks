<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhook\Bridge\Laravel\Providers;

use EoneoPay\Webhook\Bridge\Laravel\Providers\WebhookServiceProvider;
use EoneoPay\Webhook\Events\Interfaces\WebhookEventDispatcherInterface;
use Tests\EoneoPay\Webhook\WebhookTestCase;

/**
 * @covers \EoneoPay\Webhook\Bridge\Laravel\Providers\WebhookServiceProvider
 */
class WebhookServiceProviderTest extends WebhookTestCase
{
    /**
     * Test provider register container.
     *
     * @return void
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testRegister(): void
    {
        (new WebhookServiceProvider($this->getApplication()))->register();

        self::assertInstanceOf(
            WebhookEventDispatcherInterface::class,
            $this->getApplication()->get(WebhookEventDispatcherInterface::class)
        );
    }
}
