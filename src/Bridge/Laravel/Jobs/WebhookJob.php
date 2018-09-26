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
     * WebhookJob constructor.
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventInterface $event
     */
    public function __construct(EventInterface $event)
    {
        $this->event = $event;
    }

    /**
     * Handle webhook event job.
     *
     * @param \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface $httpClient
     *
     * @return \EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface|null
     *
     * @throws \EoneoPay\Externals\HttpClient\Exceptions\InvalidApiResponseException
     */
    public function handle(ClientInterface $httpClient): ?ResponseInterface
    {
        // make request
        return $httpClient->request(
            $this->event->getMethod(),
            $this->event->getUrl(),
            $this->event->serialize()
        );
    }
}
