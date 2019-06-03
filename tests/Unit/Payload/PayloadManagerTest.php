<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Payload;

use EoneoPay\Webhooks\Exceptions\PayloadBuilderNotFoundException;
use EoneoPay\Webhooks\Payload\Interfaces\PayloadBuilderInterface;
use EoneoPay\Webhooks\Payload\PayloadManager;
use Tests\EoneoPay\Webhooks\Stubs\Activity\ActivityDataStub;
use Tests\EoneoPay\Webhooks\Stubs\Payload\PayloadBuilderStub;
use Tests\EoneoPay\Webhooks\Stubs\Payload\UnsupportedPayloadBuilderStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Payload\PayloadManager
 */
class PayloadManagerTest extends TestCase
{
    /**
     * Tests payload builder when no builders are found.
     *
     * @return void
     */
    public function testBuildPayloadNoBuilders(): void
    {
        $this->expectException(PayloadBuilderNotFoundException::class);
        $this->expectExceptionMessage('A payload builder for "Tests\EoneoPay\Webhooks\Stubs\Activity\ActivityDataStub" could not be found.'); // phpcs:ignore

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
        $this->expectExceptionMessage('A payload builder for "Tests\EoneoPay\Webhooks\Stubs\Activity\ActivityDataStub" could not be found.'); // phpcs:ignore

        $manager = $this->getManager(new UnsupportedPayloadBuilderStub());

        $manager->buildPayload(new ActivityDataStub());
    }

    /**
     * Tests payload builder when no supported builders are found.
     *
     * @return void
     */
    public function testBuildPayload(): void
    {
        $manager = $this->getManager(new PayloadBuilderStub(['payload']));

        $expectedPayload = ['payload'];

        $payload = $manager->buildPayload(new ActivityDataStub());

        self::assertSame($expectedPayload, $payload);
    }

    /**
     * Returns the instance under test.
     *
     * @param \EoneoPay\Webhooks\Payload\Interfaces\PayloadBuilderInterface|null $builder
     *
     * @return \EoneoPay\Webhooks\Payload\PayloadManager
     */
    private function getManager(?PayloadBuilderInterface $builder = null): PayloadManager
    {
        return new PayloadManager($builder !== null ? [$builder] : []);
    }
}
