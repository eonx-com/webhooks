<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseEntityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;

class ResponseHandlerStub implements ResponseHandlerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseEntityInterface
     */
    private $nextResponse;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseEntityInterface[]
     */
    private $saved = [];

    /**
     * {@inheritdoc}
     */
    public function createNewWebhookResponse(): WebhookResponseEntityInterface
    {
        return $this->nextResponse;
    }

    /**
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseEntityInterface[]
     */
    public function getSaved(): array
    {
        return $this->saved;
    }

    /**
     * {@inheritdoc}
     */
    public function save(WebhookResponseEntityInterface $webhook): void
    {
        $this->saved[] = $webhook;
    }

    /**
     * Set next response
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookResponseEntityInterface $response
     *
     * @return void
     */
    public function setNextResponse(WebhookResponseEntityInterface $response): void
    {
        $this->nextResponse = $response;
    }
}
