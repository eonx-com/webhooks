<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\Schemas;

use EoneoPay\Externals\HttpClient\Response;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\Schemas\ResponseSchemaStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookEntityStub;
use Tests\EoneoPay\Webhooks\TestCase;

class ResponseSchemaTest extends TestCase
{
    /**
     * Tests webhook response schema populate
     *
     * @return void
     */
    public function testPopulate(): void
    {
        $response = new Response(null, 204);

        $schema = new ResponseSchemaStub();
        $schema->populate(new WebhookEntityStub(1), $response);

        static::assertSame($response, $schema->getResponse());
        static::assertSame(1, $schema->getSequence());
    }
}
