<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Payload;

use EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface;
use EoneoPay\Webhooks\Payloads\Interfaces\PayloadBuilderInterface;

/**
 * @coversNothing
 */
class UnsupportedPayloadBuilderStub implements PayloadBuilderInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildPayload(ActivityDataInterface $activityData): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ActivityDataInterface $data): bool
    {
        return false;
    }
}
