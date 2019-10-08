<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Schemas\Lifecycle;

use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\Lifecycle\RequestStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Schemas\Lifecycle\ResponseSchemaStub;
use Tests\EoneoPay\Webhooks\TestCase;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Schemas\Lifecycle\ResponseSchema
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
