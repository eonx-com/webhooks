<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Payload;

use EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface;
use EoneoPay\Webhooks\Payload\Interfaces\PayloadManagerInterface;

/**
 * @coversNothing
 */
class PayloadManagerStub implements PayloadManagerInterface
{
    /**
     * @var mixed[][]
     */
    private $payloads = [];

    /**
     * Adds a payload to be returned.
     *
     * @param mixed[] $payload
     *
     * @return void
     */
    public function addPayload(array $payload): void
    {
        $this->payloads[] = $payload;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPayload(ActivityDataInterface $activityData): array
    {
        return \array_shift($this->payloads) ?? [];
    }
}
