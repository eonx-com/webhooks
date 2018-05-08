<?php
declare(strict_types=1);

namespace EoneoPay\Webhook\Bridge\Laravel\Jobs;

use EoneoPay\Webhook\Jobs\Interfaces\WebhookJobDispatcherInterface;
use EoneoPay\Webhook\Jobs\Interfaces\WebhookJobInterface;
use Illuminate\Bus\Dispatcher as IlluminateJobDispatcher;

class WebhookJobDispatcher implements WebhookJobDispatcherInterface
{
    /**
     * @var \Illuminate\Bus\Dispatcher
     */
    private $jobDispatcher;

    /**
     * Constructor.
     *
     * @param \Illuminate\Bus\Dispatcher $jobDispatcher
     */
    public function __construct(IlluminateJobDispatcher $jobDispatcher)
    {
        $this->jobDispatcher = $jobDispatcher;
    }

    /**
     * Dispatch a job to handler.
     *
     * @param \EoneoPay\Webhook\Jobs\Interfaces\WebhookJobInterface $job
     *
     * @return mixed
     */
    public function dispatch(WebhookJobInterface $job)
    {
        return $this->jobDispatcher->dispatch($job);
    }
}
