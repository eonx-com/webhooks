<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Jobs;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WebhookJob implements WebhookJobInterface, ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\EventInterface
     */
    protected $event;

    /**
     * @var \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface
     */
    protected $httpClient;

    /**
     * WebhookJob constructor.
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface $httpClient
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     */
    public function __construct(ClientInterface $httpClient, EventInterface $event)
    {
        $this->httpClient = $httpClient;
        $this->event = $event;
    }

    /**
     * Handle webhook event job.
     *
     * @return \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface|null
     *
     * @throws \EoneoPay\Externals\HttpClient\Exceptions\InvalidApiResponseException
     */
    public function handle(): ?ResponseInterface
    {
        // make request
        return $this->httpClient->request(
            $this->event->getMethod(),
            $this->event->getUrl(),
            $this->event->serialize()
        );
    }
}
