<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\Schemas;

use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\Schemas\ResponseSchemaStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookRequestStub;
use Tests\EoneoPay\Webhooks\TestCase;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Schemas\WebhookResponseSchema
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
        $schema->populate(
            new WebhookRequestStub(1),
            new EmptyResponse(),
            'RESPONSE'
        );

        static::assertSame('RESPONSE', $schema->getResponse());
        static::assertSame(204, $schema->getStatusCode());
    }
}
