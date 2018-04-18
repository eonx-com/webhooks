<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Bridge\Laravel\Providers;

use EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookServiceProvider;
use EoneoPay\Webhooks\Events\Interfaces\WebhookEventDispatcherInterface;
use Tests\EoneoPay\Webhooks\WebhookTestCase;

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
