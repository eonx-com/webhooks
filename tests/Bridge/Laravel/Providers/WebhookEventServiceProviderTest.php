<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Bridge\Laravel\Providers;

use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookEvent;
use EoneoPay\Webhooks\Bridge\Laravel\Providers\WebhookEventServiceProvider;
use Tests\EoneoPay\Webhooks\WebhookTestCase;

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
        self::assertArrayHasKey(WebhookEvent::class, $provider->listens());
    }
}
