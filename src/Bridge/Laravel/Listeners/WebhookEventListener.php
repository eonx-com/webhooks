<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Listeners;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Webhooks\Bridge\Laravel\Jobs\WebhookJob;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface;
use EoneoPay\Webhooks\Listeners\Interfaces\WebhookEventListenerInterface;

class WebhookEventListener implements WebhookEventListenerInterface
{
    /**
     * @var \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface
     */
    private $httpClient;

    /**
     * @var \EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface
     */
    private $jobDispatcher;

    /**
     *  Constructor.
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface $httpClient
     * @param \EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface $jobDispatcher
     */
    public function __construct(ClientInterface $httpClient, WebhookJobDispatcherInterface $jobDispatcher)
    {
        $this->httpClient = $httpClient;
        $this->jobDispatcher = $jobDispatcher;
    }

    /**
     * Handle a webhook event.
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     *
     * @return mixed
     */
    public function handle(EventInterface $event)
    {
        // dispatch webhook job
        return $this->jobDispatcher->dispatch(new WebhookJob($this->httpClient, $event));
    }
}
