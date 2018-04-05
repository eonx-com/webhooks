<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Listeners;

use EoneoPay\External\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Webhook\Bridge\Laravel\Jobs\WebhookJob;
use EoneoPay\Webhook\Events\Interfaces\WebhookEventInterface;
use EoneoPay\Webhook\Jobs\Interfaces\WebhookJobDispatcherInterface;
use EoneoPay\Webhook\Listeners\Interfaces\WebhookEventListenerInterface;

class WebhookEventListener implements WebhookEventListenerInterface
{
    /** @var \EoneoPay\External\HttpClient\Interfaces\ClientInterface */
    private $httpClient;

    /** @var \EoneoPay\Webhook\Jobs\Interfaces\WebhookJobDispatcherInterface */
    private $dispatcher;

    /**
     *  Constructor.
     *
     * @param \EoneoPay\External\HttpClient\Interfaces\ClientInterface $httpClient
     * @param \EoneoPay\Webhook\Jobs\Interfaces\WebhookJobDispatcherInterface $dispatcher
     */
    public function __construct(ClientInterface $httpClient, WebhookJobDispatcherInterface $dispatcher)
    {
        $this->httpClient = $httpClient;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle a webhook event.
     *
     * @param WebhookEventInterface $event
     * @return mixed
     */
    public function handle(WebhookEventInterface $event)
    {
        // dispatch webhook job
        return $this->dispatcher->dispatch(new WebhookJob($this->httpClient, $event));
    }
}
