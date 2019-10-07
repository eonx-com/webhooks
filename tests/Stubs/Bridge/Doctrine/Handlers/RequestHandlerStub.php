<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;

/**
 * @coversNothing
 */
class RequestHandlerStub implements RequestHandlerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Models\WebhookRequestInterface
     */
    private $nextRequest;

    /**
     * @var \EoneoPay\Webhooks\Models\WebhookRequestInterface[]
     */
    private $saved = [];

    /**
     * {@inheritdoc}
     */
    public function create(): WebhookRequestInterface
    {
        return $this->nextRequest;
    }

    /**
     * {@inheritdoc}
     */
    public function getBySequence(int $sequence): WebhookRequestInterface
    {
        return $this->nextRequest;
    }

    /**
     * @return \EoneoPay\Webhooks\Models\WebhookRequestInterface[]
     */
    public function getSaved(): array
    {
        return $this->saved;
    }

    /**
     * {@inheritdoc}
     */
    public function save(WebhookRequestInterface $webhook): void
    {
        $this->saved[] = $webhook;
    }

    /**
     * Set next webhook.
     *
     * @param \EoneoPay\Webhooks\Models\WebhookRequestInterface $entity
     *
     * @return void
     */
    public function setNextRequest(WebhookRequestInterface $entity): void
    {
        $this->nextRequest = $entity;
    }
}
