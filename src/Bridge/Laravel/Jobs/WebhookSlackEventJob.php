<?php declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Jobs;

use EoneoPay\External\HttpClient\Interfaces\ResponseInterface;

class WebhookSlackEventJob extends WebhookJob
{
    /**
     * Handle webhook Slack event job.
     *
     * @return \EoneoPay\External\HttpClient\Interfaces\ResponseInterface
     * @throws \EoneoPay\External\HttpClient\Exceptions\InvalidApiResponseException
     */
    public function handle(): ResponseInterface
    {
        return $this->httpClient->request('POST', $this->event->get('url'), [
            'json' => $this->event->get('payload')
        ]);
    }
}
