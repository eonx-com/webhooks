<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Listeners;

use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestRetryEvent;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface;

/**
 * This listener will listen for retry events created by Laravel bridge's event dispatcher
 * and call request processor to process the webhook request
 */
class RequestRetryListener
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface
     */
    private $requestHandler;

    /**
     * @var \EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface
     */
    private $requestProcessor;

    /**
     * Constructor
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface $requestHandler
     * @param \EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface $requestProcessor
     */
    public function __construct(
        RequestHandlerInterface $requestHandler,
        RequestProcessorInterface $requestProcessor
    ) {
        $this->requestHandler = $requestHandler;
        $this->requestProcessor = $requestProcessor;
    }

    /**
     * Handles the WebhookRequestRetry event.
     *
     * @param \EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestRetryEvent $event
     *
     * @return void
     */
    public function handle(WebhookRequestRetryEvent $event): void
    {
        $request = $this->requestHandler->getBySequence($event->getRequestId());

        $this->requestProcessor->process($request);
    }
}
