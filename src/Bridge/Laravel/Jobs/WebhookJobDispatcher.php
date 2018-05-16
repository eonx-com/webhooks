<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Jobs;

use EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface;
use Illuminate\Bus\Dispatcher as IlluminateDispatcher;

class WebhookJobDispatcher implements WebhookJobDispatcherInterface
{
    /** @var \Illuminate\Bus\Dispatcher  */
    private $dispatcher;

    /**
     * Constructor.
     *
     * @param \Illuminate\Bus\Dispatcher $dispatcher
     */
    public function __construct(IlluminateDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Dispatch a command to handler.
     *
     * @param mixed $command
     * @return mixed
     */
    public function dispatch($command)
    {
        return $this->dispatcher->dispatch($command);
    }
}
