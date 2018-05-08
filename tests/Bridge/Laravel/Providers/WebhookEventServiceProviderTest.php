<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhook\Bridge\Laravel\Providers;

use EoneoPay\Webhook\Bridge\Laravel\Providers\WebhookEventServiceProvider;
use EoneoPay\Webhook\Events\Interfaces\EventInterface;
use Tests\EoneoPay\Webhook\WebhookTestCase;

/**
 * @covers \EoneoPay\Webhook\Bridge\Laravel\Providers\WebhookEventServiceProvider
 */
class WebhookEventServiceProviderTest extends WebhookTestCase
{
    /**
     * Test provider register container.
     *
     * @return void
     */
    public function testListens(): void
    {
        // create provider
        $provider = new WebhookEventServiceProvider($this->getApplication());
        // register provider
        $provider->register();
        // assertions
        self::assertArrayHasKey(EventInterface::class, $provider->listens());
    }
}
