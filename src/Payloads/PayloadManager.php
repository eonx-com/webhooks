<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Payloads;

use EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface;
use EoneoPay\Webhooks\Exceptions\PayloadBuilderNotFoundException;
use EoneoPay\Webhooks\Payloads\Interfaces\PayloadBuilderInterface;
use EoneoPay\Webhooks\Payloads\Interfaces\PayloadManagerInterface;

final class PayloadManager implements PayloadManagerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Payloads\Interfaces\PayloadBuilderInterface[]
     */
    private $payloadBuilders;

    /**
     * Constructor.
     *
     * @param \EoneoPay\Webhooks\Payloads\Interfaces\PayloadBuilderInterface[] $payloadBuilders
     */
    public function __construct(array $payloadBuilders)
    {
        $this->payloadBuilders = $payloadBuilders;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \EoneoPay\Webhooks\Exceptions\PayloadBuilderNotFoundException
     */
    public function buildPayload(ActivityDataInterface $activityData): array
    {
        $builder = $this->getPayloadBuilder($activityData);

        return $builder->buildPayload($activityData);
    }

    /**
     * Finds a payload builder for the specific activity.
     *
     * @param \EoneoPay\Webhooks\Activities\Interfaces\ActivityDataInterface $activityData
     *
     * @return \EoneoPay\Webhooks\Payloads\Interfaces\PayloadBuilderInterface
     *
     * @throws \EoneoPay\Webhooks\Exceptions\PayloadBuilderNotFoundException
     */
    private function getPayloadBuilder(ActivityDataInterface $activityData): PayloadBuilderInterface
    {
        foreach ($this->payloadBuilders as $builder) {
            if ($builder->supports($activityData) === true) {
                return $builder;
            }
        }

        throw new PayloadBuilderNotFoundException(\sprintf(
            'A payload builder for "%s" could not be found.',
            \get_class($activityData)
        ));
    }
}
