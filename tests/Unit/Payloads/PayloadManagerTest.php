<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Payloads;

use EoneoPay\Webhooks\Exceptions\PayloadBuilderNotFoundException;
use EoneoPay\Webhooks\Payloads\PayloadManager;
use Tests\EoneoPay\Webhooks\Stubs\Activities\ActivityDataStub;
use Tests\EoneoPay\Webhooks\Stubs\Payloads\PayloadBuilderStub;
use Tests\EoneoPay\Webhooks\Stubs\Payloads\UnsupportedPayloadBuilderStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Payloads\PayloadManager
 */
class PayloadManagerTest extends TestCase
{
    /**
     * Tests payload builder when no supported builders are found.
     *
     * @return void
     */
    public function testBuildPayload(): void
    {
        $manager = $this->getManager([new PayloadBuilderStub(['payload'])]);

        $expectedPayload = ['payload'];

        $payload = $manager->buildPayload(new ActivityDataStub());

        self::assertSame($expectedPayload, $payload);
    }

    /**
     * Tests payload builder when no supported builders are found.
     *
     * @return void
     */
    public function testBuildPayloadMultiple(): void
    {
        $manager = $this->getManager([
            new UnsupportedPayloadBuilderStub(),
            new PayloadBuilderStub(['payload']),
        ]);

        $expectedPayload = ['payload'];

        $payload = $manager->buildPayload(new ActivityDataStub());

        self::assertSame($expectedPayload, $payload);
    }

    /**
     * Tests payload builder when no builders are found.
     *
     * @return void
     */
    public function testBuildPayloadNoBuilders(): void
    {
        $this->expectException(PayloadBuilderNotFoundException::class);
        $this->expectExceptionMessage(\sprintf(
            'A payload builder for "%s" could not be found.',
            ActivityDataStub::class
        ));

        $manager = $this->getManager();

        $manager->buildPayload(new ActivityDataStub());
    }

    /**
     * Tests payload builder when no supported builders are found.
     *
     * @return void
     */
    public function testBuildPayloadNoSupportedBuilders(): void
    {
        $this->expectException(PayloadBuilderNotFoundException::class);
        $this->expectExceptionMessage(\sprintf(
            'A payload builder for "%s" could not be found.',
            ActivityDataStub::class
        ));

        $manager = $this->getManager([new UnsupportedPayloadBuilderStub()]);

        $manager->buildPayload(new ActivityDataStub());
    }

    /**
     * Returns the instance under test.
     *
     * @param \EoneoPay\Webhooks\Payloads\Interfaces\PayloadBuilderInterface[]|null $builders
     *
     * @return \EoneoPay\Webhooks\Payloads\PayloadManager
     */
    private function getManager(?array $builders = null): PayloadManager
    {
        return new PayloadManager($builders ?? []);
    }
}
