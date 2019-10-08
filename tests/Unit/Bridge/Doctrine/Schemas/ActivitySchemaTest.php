<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Schemas;

use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EntityStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Schemas\ActivitySchema
 */
class ActivitySchemaTest extends TestCase
{
    /**
     * Tests setPrimaryEntity.
     *
     * @return void
     */
    public function testSetPrimaryEntity(): void
    {
        $schema = new ActivityStub();
        $schema->setPrimaryEntity(new EntityStub());

        self::assertSame(EntityStub::class, $schema->getPrimaryClass());
        self::assertSame('1', $schema->getPrimaryId());
    }
}
