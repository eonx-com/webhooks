<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Persister;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\WebhookHandlerInterface;
use EoneoPay\Webhooks\Exceptions\WebhookSequenceMissingException;
use EoneoPay\Webhooks\Persister\Interfaces\WebhookPersisterInterface;
use EoneoPay\Webhooks\Subscription\Interfaces\SubscriptionInterface;

final class WebhookPersister implements WebhookPersisterInterface
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface
     */
    private $responseHandler;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\WebhookHandlerInterface
     */
    private $webhookHandler;

    /**
     * Constructor
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\WebhookHandlerInterface $webhookHandler
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ResponseHandlerInterface $responseHandler
     */
    public function __construct(
        WebhookHandlerInterface $webhookHandler,
        ResponseHandlerInterface $responseHandler
    ) {
        $this->webhookHandler = $webhookHandler;
        $this->responseHandler = $responseHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function save(string $event, array $payload, SubscriptionInterface $subscription): int
    {
        $webhook = $this->webhookHandler->createNewWebhook();
        $webhook->populate($event, $payload, $subscription);

        $this->webhookHandler->save($webhook);

        if ($webhook->getSequence() === null) {
            throw new WebhookSequenceMissingException('The webhook didnt return a usable sequence number');
        }

        return $webhook->getSequence();
    }

    /**
     * {@inheritdoc}
     */
    public function update(int $sequence, ResponseInterface $response): void
    {
        $webhook = $this->webhookHandler->getWebhook($sequence);

        $webhookResponse = $this->responseHandler->createNewWebhookResponse();
        $webhookResponse->populate($webhook, $response);

        $this->responseHandler->save($webhookResponse);
    }
}
