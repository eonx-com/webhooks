<?php declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Listeners;

use EoneoPay\External\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Webhook\Bridge\Laravel\Events\WebhookHttpEvent;
use EoneoPay\Webhook\Bridge\Laravel\Events\WebhookSlackEvent;
use EoneoPay\Webhook\Bridge\Laravel\Jobs\WebhookHttpEventJob;
use EoneoPay\Webhook\Bridge\Laravel\Jobs\WebhookSlackEventJob;
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
     * Handle an event.
     *
     * @param mixed $event
     *
     * @return mixed|null
     */
    public function handle($event)
    {
        if ($event instanceof WebhookSlackEvent) :
            // add Slack job to the queue
            return $this->dispatcher->dispatch(new WebhookSlackEventJob($this->httpClient, $event));
        elseif ($event instanceof WebhookHttpEvent) :
            // add HTTP job to the queue
            return $this->dispatcher->dispatch(new WebhookHttpEventJob($this->httpClient, $event));
        endif;

        return null;
    }
}
