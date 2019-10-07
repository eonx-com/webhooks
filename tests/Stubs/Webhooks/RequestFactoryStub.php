<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Webhooks;

use EoneoPay\Webhooks\Models\ActivityInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestFactoryInterface;

/**
 * @coversNothing
 */
class RequestFactoryStub implements RequestFactoryInterface
{
    /**
     * @var \EoneoPay\Webhooks\Models\ActivityInterface[]
     */
    private $processed = [];

    /**
     * Returns processed activities.
     *
     * @return \EoneoPay\Webhooks\Models\ActivityInterface[]
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
