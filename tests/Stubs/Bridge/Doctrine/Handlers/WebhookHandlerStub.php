<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers;

use EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\WebhookHandlerInterface;

class WebhookHandlerStub implements WebhookHandlerInterface
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface
     */
    private $nextWebhook;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface[]
     */
    private $saved = [];

    /**
     * @inheritdoc
     */
    public function createNewWebhook(): WebhookEntityInterface
    {
        return $this->nextWebhook;
    }

    /**
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface[]
     */
    public function getSaved(): array
    {
        return $this->saved;
    }

    /**
     * @inheritdoc
     */
    public function getWebhook(int $sequence): WebhookEntityInterface
    {
        return $this->nextWebhook;
    }

    /**
     * @inheritdoc
     */
    public function save(WebhookEntityInterface $webhook): void
    {
        $this->saved[] = $webhook;
    }

    /**
     * Set next webhook
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entity\WebhookEntityInterface $entity
     *
     * @return void
     */
    public function setNextWebhook(WebhookEntityInterface $entity): void
    {
        $this->nextWebhook = $entity;
    }
}
