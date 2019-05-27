<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Providers;

use EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookEventServiceProvider;
use Tests\EoneoPay\Webhooks\WebhookTestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookEventServiceProvider
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
        $provider = new WebhookEventServiceProvider($this->createApplication());

        // register provider
        $provider->register();

        self::assertEmpty($provider->listens());
    }
}
