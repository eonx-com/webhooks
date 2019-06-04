<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Listeners;

use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\RequestHandlerInterface;
use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestCreatedEvent;
use EoneoPay\Webhooks\Webhooks\Interfaces\RequestProcessorInterface;

/**
 * This listener will listen for events created by the Laravel Bridge's EventDispatcher
 * and call out to services that expect to be notified when the event is raised.
 *
 * It is the primary entrypoint in queue workers for when a webhook request is created
 * and calls out to the Request Processor to notify it that a request has been created
 * and needs to be actioned.
 */
class RequestCreatedListener
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
     * Handles the WebhookRequestCreated event.
     *
     * @param \EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestCreatedEvent $event
     *
     * @return void
     */
    public function handle(WebhookRequestCreatedEvent $event): void
    {
        $request = $this->requestHandler->getBySequence($event->getRequestId());

        $this->requestProcessor->process($request);
    }
}
