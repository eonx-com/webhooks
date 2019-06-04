<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Webhooks;

use EoneoPay\Webhooks\Model\ActivityInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestFactoryInterface;

/**
 * @coversNothing
 */
class RequestFactoryStub implements RequestFactoryInterface
{
    /**
     * @var \EoneoPay\Webhooks\Model\ActivityInterface[]
     */
    private $processed = [];

    /**
     * Returns processed activities.
     *
     * @return \EoneoPay\Webhooks\Model\ActivityInterface[]
     */
    public function getProcessed(): array
    {
        return $this->processed;
    }

    /**
     * {@inheritdoc}
     */
    public function processActivity(ActivityInterface $activity): void
    {
        $this->processed[] = $activity;
    }
}
