<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Payload;

use EoneoPay\Webhooks\Activity\Interfaces\ActivityDataInterface;
use EoneoPay\Webhooks\Exceptions\PayloadBuilderNotFoundException;
use EoneoPay\Webhooks\Payload\Interfaces\PayloadBuilderInterface;
use EoneoPay\Webhooks\Payload\Interfaces\PayloadManagerInterface;

class PayloadManager implements PayloadManagerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Payload\Interfaces\PayloadBuilderInterface[]
     */
    private $payloadBuilders;

    /**
     * Constructor
     *
     * @param \EoneoPay\Webhooks\Payload\Interfaces\PayloadBuilderInterface[] $payloadBuilders
     */
    public function __construct(iterable $payloadBuilders)
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
     * @param \EoneoPay\Webhooks\Activity\Interfaces\ActivityDataInterface $activityData
     *
     * @return \EoneoPay\Webhooks\Payload\Interfaces\PayloadBuilderInterface
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
