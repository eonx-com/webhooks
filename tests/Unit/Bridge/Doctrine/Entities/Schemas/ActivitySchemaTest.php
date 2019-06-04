<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\Schemas;

use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\ActivityStub;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EntityStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Schemas\ActivitySchema
 */
class ActivitySchemaTest extends TestCase
{
    /**
     * Tests setPrimaryEntity
     *
     * @return void
     */
    public function testSetPrimaryEntity(): void
    {
        $schema = new ActivityStub();
        $schema->setPrimaryEntity(new EntityStub());

        static::assertSame(EntityStub::class, $schema->getPrimaryClass());
        static::assertSame('1', $schema->getPrimaryId());
    }
}
