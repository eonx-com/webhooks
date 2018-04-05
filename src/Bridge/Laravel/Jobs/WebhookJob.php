<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Jobs;

use EoneoPay\External\HttpClient\Interfaces\ClientInterface;
use EoneoPay\External\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Webhook\Events\Interfaces\WebhookEventInterface;
use EoneoPay\Webhook\Jobs\Interfaces\WebhookJobInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WebhookJob implements WebhookJobInterface, ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /** @var WebhookEventInterface */
    protected $event;

    /** @var \EoneoPay\External\HttpClient\Interfaces\ClientInterface */
    protected $httpClient;

    /**
     * WebhookJob constructor.
     *
     * @param ClientInterface $httpClient
     * @param WebhookEventInterface $event
     */
    public function __construct(ClientInterface $httpClient, WebhookEventInterface $event)
    {
        $this->httpClient = $httpClient;
        $this->event = $event;
    }

    /**
     * Handle webhook event job.
     *
     * @return \EoneoPay\External\HttpClient\Interfaces\ResponseInterface
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
