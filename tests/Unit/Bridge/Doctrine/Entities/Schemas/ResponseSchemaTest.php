<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\Schemas;

use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\Schemas\ResponseSchemaStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookRequestStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Entity\Schemas\WebhookResponseSchema
 */
class ResponseSchemaTest extends TestCase
{
    /**
     * Tests webhook response schema populate
     *
     * @return void
     */
    public function testPopulate(): void
    {
        $schema = new ResponseSchemaStub();
        $schema->populate(new WebhookRequestStub(1), 'RESPONSE');

        static::assertSame('RESPONSE', $schema->getResponse());
        static::assertSame(1, $schema->getSequence());
    }
}
