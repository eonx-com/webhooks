<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\Schemas;

use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\Schemas\RequestSchemaStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Schemas\WebhookRequestSchema
 */
class RequestSchemaTest extends TestCase
{
    /**
     * Tests webhook schema populate
     *
     * @return void
     */
    public function testPopulate(): void
    {
        $subscription = new SubscriptionStub();

        $schema = new RequestSchemaStub();
        $schema->populate(new ActivityStub(), $subscription);

        static::assertSame('json', $schema->getRequestFormat());
        static::assertSame(['authorization' => 'Bearer ABC123'], $schema->getRequestHeaders());
        static::assertSame('POST', $schema->getRequestMethod());
        static::assertSame('https://127.0.0.1/webhook', $schema->getRequestUrl());
    }
}
