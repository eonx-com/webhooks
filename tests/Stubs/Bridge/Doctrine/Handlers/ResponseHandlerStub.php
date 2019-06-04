<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Model\WebhookResponseInterface;

/**
 * @coversNothing
 */
class ResponseHandlerStub implements ResponseHandlerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Model\WebhookResponseInterface
     */
    private $nextResponse;

    /**
     * @var \EoneoPay\Webhooks\Model\WebhookResponseInterface[]
     */
    private $saved = [];

    /**
     * {@inheritdoc}
     */
    public function createNewWebhookResponse(): WebhookResponseInterface
    {
        return $this->nextResponse;
    }

    /**
     * @return \EoneoPay\Webhooks\Model\WebhookResponseInterface[]
     */
    public function getSaved(): array
    {
        return $this->saved;
    }

    /**
     * {@inheritdoc}
     */
    public function save(WebhookResponseInterface $webhook): void
    {
        $this->saved[] = $webhook;
    }

    /**
     * Set next response
     *
     * @param \EoneoPay\Webhooks\Model\WebhookResponseInterface $response
     *
     * @return void
     */
    public function setNextResponse(WebhookResponseInterface $response): void
    {
        $this->nextResponse = $response;
    }
}
