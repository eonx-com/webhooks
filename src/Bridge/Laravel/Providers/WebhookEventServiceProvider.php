<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Laravel\Providers;

use Laravel\Lumen\Providers\EventServiceProvider;

class WebhookEventServiceProvider extends EventServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function __construct($app)
    {
        parent::__construct($app);

        // Set listeners
        $this->listen = [];
    }
}
