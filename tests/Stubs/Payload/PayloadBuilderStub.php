<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Payload;

use EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface;
use EoneoPay\Webhooks\Payloads\Interfaces\PayloadBuilderInterface;

/**
 * @coversNothing
 */
class PayloadBuilderStub implements PayloadBuilderInterface
{
    /**
     * @var mixed[]
     */
    private $payload;

    /**
     * Constructor.
     *
     * @param mixed[] $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * {@inheritdoc}
     */
    public function buildPayload(ActivityDataInterface $activityData): array
    {
        return $this->payload;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ActivityDataInterface $data): bool
    {
        return true;
    }
}
