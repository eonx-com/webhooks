<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;

class RequestHandlerStub implements RequestHandlerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface
     */
    private $nextWebhook;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface[]
     */
    private $saved = [];

    /**
     * {@inheritdoc}
     */
    public function create(): WebhookRequestInterface
    {
        return $this->nextWebhook;
    }

    /**
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface[]
     */
    public function getSaved(): array
    {
        return $this->saved;
    }

    /**
     * {@inheritdoc}
     */
    public function getBySequence(int $sequence): WebhookRequestInterface
    {
        return $this->nextWebhook;
    }

    /**
     * {@inheritdoc}
     */
    public function save(WebhookRequestInterface $webhook): void
    {
        $this->saved[] = $webhook;
    }

    /**
     * Set next webhook
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookRequestInterface $entity
     *
     * @return void
     */
    public function setNextWebhook(WebhookRequestInterface $entity): void
    {
        $this->nextWebhook = $entity;
    }
}
