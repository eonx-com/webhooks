<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Exceptions;

use EoneoPay\Webhooks\Exceptions\UnknownSerialisationFormatException;
use Tests\EoneoPay\Webhooks\TestCase;

class UnknownSerialisationFormatExceptionTest extends TestCase
{
    /**
     * Test exception message is correctly formatted
     *
     * @return void
     */
    public function testExceptionCreation(): void
    {
        $exception = new UnknownSerialisationFormatException('json');

        static::assertSame('Unknown serialisation format "json"', $exception->getMessage());
    }
}
