<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Listeners;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;

final class WebhookEventListener
{
    /**
     * @var \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface
     */
    private $httpClient;

    /**
     * Constructor.
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Handle a webhook event.
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     *
     * @return void
     */
    public function handle(EventInterface $event): void
    {
        $this->httpClient->request(
            $event->getMethod(),
            $event->getUrl(),
            [
                'body' => $event->getPayload(),
                'headers' => $event->getHeaders()
            ]
        );
    }
}
