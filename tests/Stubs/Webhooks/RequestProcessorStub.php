<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Webhooks;

use EoneoPay\Webhooks\Models\WebhookRequestInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface;

/**
 * @coversNothing
 */
class RequestProcessorStub implements RequestProcessorInterface
{
    /**
     * @var \EoneoPay\Webhooks\Models\WebhookRequestInterface[]
     */
    private $processed = [];

    /**
     * Returns processed.
     *
     * @return \EoneoPay\Webhooks\Models\WebhookRequestInterface[]
     */
    public function getProcessed(): array
    {
        return $this->processed;
    }

    /**
     * {@inheritdoc}
     */
    public function process(WebhookRequestInterface $webhookRequest): void
    {
        $this->processed[] = $webhookRequest;
    }
}
