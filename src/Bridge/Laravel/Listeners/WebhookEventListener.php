<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Listeners;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Webhook\Bridge\Laravel\Jobs\WebhookJob;
use EoneoPay\Webhook\Events\Interfaces\EventInterface;
use EoneoPay\Webhook\Jobs\Interfaces\WebhookJobDispatcherInterface;
use EoneoPay\Webhook\Listeners\Interfaces\WebhookEventListenerInterface;

class WebhookEventListener implements WebhookEventListenerInterface
{
    /**
     * @var \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface
     */
    private $httpClient;

    /**
     * @var \EoneoPay\Webhook\Jobs\Interfaces\WebhookJobDispatcherInterface
     */
    private $jobDispatcher;

    /**
     *  Constructor.
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface $httpClient
     * @param \EoneoPay\Webhook\Jobs\Interfaces\WebhookJobDispatcherInterface $jobDispatcher
     */
    public function __construct(ClientInterface $httpClient, WebhookJobDispatcherInterface $jobDispatcher)
    {
        $this->httpClient = $httpClient;
        $this->jobDispatcher = $jobDispatcher;
    }

    /**
     * Handle a webhook event.
     *
     * @param \EoneoPay\Webhook\Events\Interfaces\EventInterface $event
     *
     * @return mixed
     */
    public function handle(EventInterface $event)
    {
        // dispatch webhook job
        return $this->jobDispatcher->dispatch(new WebhookJob($this->httpClient, $event));
    }
}
