<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Listeners;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Webhooks\Bridge\Laravel\Jobs\WebhookJob;
use EoneoPay\Webhooks\Events\Interfaces\WebhookEventInterface;
use EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface;
use EoneoPay\Webhooks\Listeners\Interfaces\WebhookEventListenerInterface;

class WebhookEventListener implements WebhookEventListenerInterface
{
    /** @var \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface */
    private $httpClient;

    /** @var \EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface */
    private $dispatcher;

    /**
     *  Constructor.
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface $httpClient
     * @param \EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface $dispatcher
     */
    public function __construct(ClientInterface $httpClient, WebhookJobDispatcherInterface $dispatcher)
    {
        $this->httpClient = $httpClient;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle a webhook event.
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\WebhookEventInterface $event
     * @return mixed
     */
    public function handle(WebhookEventInterface $event)
    {
        // dispatch webhook job
        return $this->dispatcher->dispatch(new WebhookJob($this->httpClient, $event));
    }
}
