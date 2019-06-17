<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities;

use EoneoPay\Utils\DateTime;
use EoneoPay\Utils\Interfaces\UtcDateTimeInterface;
use PHPStan\Testing\TestCase;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\EntityStub;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Entity
 */
class EntityTest extends TestCase
{
    /**
     * Test date formatter
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException If timestamp is invalid for DateTime
     */
    public function testFormatDate(): void
    {
        $entity = new EntityStub();

        // If date is null, null should be returned
        self::assertNull($entity->formatDate(null));

        $datetime = new DateTime();

        // Date should be formatted to zulu
        self::assertSame($datetime->format(UtcDateTimeInterface::FORMAT_ZULU), $entity->formatDate($datetime));
    }
}
