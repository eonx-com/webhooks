<?php declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Jobs;

use EoneoPay\External\HttpClient\Interfaces\ResponseInterface;

class WebhookHttpEventJob extends WebhookJob
{
    /**
     * Handle webhook HTTP event job.
     *
     * @return \EoneoPay\External\HttpClient\Interfaces\ResponseInterface
     * @throws \EoneoPay\External\HttpClient\Exceptions\InvalidApiResponseException
     */
    public function handle(): ResponseInterface
    {
        $auth = [
            $this->event->get('username'),
            $this->event->get('password'),
            $this->event->get('auth_type')
        ];

        return $this->httpClient->request('POST', $this->event->get('url'), [
            'auth' => $auth,
            'json' => $this->event->get('payload')
        ]);
    }
}
