<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;

class ResponseHandlerStub implements ResponseHandlerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseInterface
     */
    private $nextResponse;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseInterface[]
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
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseInterface[]
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
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseInterface $response
     *
     * @return void
     */
    public function setNextResponse(WebhookResponseInterface $response): void
    {
        $this->nextResponse = $response;
    }
}
