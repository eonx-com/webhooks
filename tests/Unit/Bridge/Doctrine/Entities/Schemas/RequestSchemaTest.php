<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\Schemas;

use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Schemas\Webhooks\RequestSchemaStub;
use Tests\EoneoPay\Webhooks\Stubs\Subscription\SubscriptionStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Schemas\Webhooks\RequestSchema
 */
class RequestSchemaTest extends TestCase
{
    /**
     * Tests webhook schema populate.
     *
     * @return void
     */
    public function testPopulate(): void
    {
        $subscription = new SubscriptionStub();

        $schema = new RequestSchemaStub();
        $schema->populate(new ActivityStub(), $subscription);

        self::assertSame('json', $schema->getRequestFormat());
        self::assertSame(['authorization' => 'Bearer ABC123'], $schema->getRequestHeaders());
        self::assertSame('POST', $schema->getRequestMethod());
        self::assertSame('https://127.0.0.1/webhook', $schema->getRequestUrl());
    }
}
