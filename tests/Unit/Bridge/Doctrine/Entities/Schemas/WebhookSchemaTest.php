<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\Schemas;

use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\Schemas\WebhookSchemaStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;

class WebhookSchemaTest extends TestCase
{
    /**
     * Tests webhook schema populate
     *
     * @return void
     */
    public function testPopulate(): void
    {
        $payload = ['payload' => 'here'];
        $subscription = new SubscriptionStub();

        $schema = new WebhookSchemaStub();
        $schema->populate('event', $payload, $subscription);

        static::assertEquals('event', $schema->getEvent());
        static::assertEquals($payload, $schema->getPayload());
        static::assertEquals('json', $schema->getRequestFormat());
        static::assertEquals(['authorization' => 'Bearer ABC123'], $schema->getRequestHeaders());
        static::assertEquals('POST', $schema->getRequestMethod());
        static::assertEquals('https://127.0.0.1/webhook', $schema->getRequestUrl());
    }
}
