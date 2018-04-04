<?php declare(strict_types=1);

namespace EoneoPay\Webhook\Jobs\Interfaces;

interface WebhookJobDispatcherInterface
{
    /**
     * Dispatch a command to its handler.
     *
     * @param $command
     * @return mixed
     */
    public function dispatch($command);
}
