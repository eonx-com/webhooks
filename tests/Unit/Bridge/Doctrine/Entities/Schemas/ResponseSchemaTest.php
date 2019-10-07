<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\Schemas;

use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\Webhooks\RequestStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Schemas\Webhooks\ResponseSchemaStub;
use Tests\EoneoPay\Webhooks\TestCase;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Schemas\Webhooks\ResponseSchema
 */
class ResponseSchemaTest extends TestCase
{
    /**
     * Tests webhook response schema populate.
     *
     * @return void
     */
    public function testPopulate(): void
    {
        $schema = new ResponseSchemaStub();
        $schema->populate(
            new RequestStub(1),
            new EmptyResponse(),
            'RESPONSE'
        );

        self::assertSame('RESPONSE', $schema->getResponse());
        self::assertSame(204, $schema->getStatusCode());
    }
}
