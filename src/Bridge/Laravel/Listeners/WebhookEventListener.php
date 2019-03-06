<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Listeners;

use EoneoPay\Webhooks\Client\Interfaces\ClientInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;

final class WebhookEventListener
{
    /**
     * @var \EoneoPay\Webhooks\Client\Interfaces\ClientInterface
     */
    private $client;

    /**
     * WebhookEventListener constructor.
     *
     * @param \EoneoPay\Webhooks\Client\Interfaces\ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
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
        $this->client->send($event);
    }
}
