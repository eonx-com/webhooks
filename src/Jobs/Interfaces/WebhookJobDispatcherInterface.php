<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Jobs\Interfaces;

interface WebhookJobDispatcherInterface
{
    /**
     * Dispatch a command to its handler.
     *
     * @param mixed $command
     * @return mixed
     */
    public function dispatch($command);
}
