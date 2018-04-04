<?php declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use EoneoPay\Webhook\Jobs\Interfaces\WebhookJobInterface;
use EoneoPay\External\HttpClient\Interfaces\ClientInterface;

abstract class WebhookJob implements WebhookJobInterface, ShouldQueue
{
    use InteractsWithQueue, Queueable;

    /** @var mixed */
    protected $event;

    /** @var \EoneoPay\External\HttpClient\Interfaces\ClientInterface */
    protected $httpClient;

    /**
     * WebhookJob constructor.
     *
     * @param \EoneoPay\External\HttpClient\Interfaces\ClientInterface $httpClient
     * @param null $event
     */
    public function __construct(ClientInterface $httpClient, $event = null)
    {
        $this->httpClient = $httpClient;
        $this->event = $event;
    }
}
