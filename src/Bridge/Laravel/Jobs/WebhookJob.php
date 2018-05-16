<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Jobs;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Webhooks\Events\Interfaces\WebhookEventInterface;
use EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WebhookJob implements WebhookJobInterface, ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /** @var \EoneoPay\Webhooks\Events\Interfaces\WebhookEventInterface */
    protected $event;

    /** @var \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface */
    protected $httpClient;

    /**
     * WebhookJob constructor.
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface $httpClient
     * @param \EoneoPay\Webhooks\Events\Interfaces\WebhookEventInterface $event
     */
    public function __construct(ClientInterface $httpClient, WebhookEventInterface $event)
    {
        $this->httpClient = $httpClient;
        $this->event = $event;
    }

    /**
     * Handle webhook event job.
     *
     * @return \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface
     */
    public function handle(): ?ResponseInterface
    {
        $postData = [
            'auth' => [
                $this->event->getUsername(),
                $this->event->getPassword(),
                $this->event->getAuthType()
            ],
            'body' => $this->event->getPayload()->serialize()
        ];

        // make request
        return $this->httpClient->request('POST', $this->event->getUrl(), $postData);
    }
}
