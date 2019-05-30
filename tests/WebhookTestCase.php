<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks;

use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Contracts\Container\Container as IlluminateContainerContract;
use Illuminate\Contracts\Events\Dispatcher as IlluminateDispatcherContract;
use Illuminate\Events\Dispatcher as IlluminateDispatcher;

/**
 * @coversNothing
 */
abstract class WebhookTestCase extends TestCase
{
    /**
     * Slack event payload.
     *
     * @var mixed[]
     */
    protected static $httpPayload = [
        'ping' => 'OK'
    ];

    /**
     * Http event URL.
     *
     * @var string
     */
    protected static $httpUrl = 'http://127.0.0.1:8000/webhook/callback';

    /**
     * Http event payload.
     *
     * @var mixed[]
     */
    protected static $slackPayload = [
        'username' => 'TestBot',
        'channel' => '#general',
        'attachments' => [
            'color' => 'good',
            'fields' => [
                'title' => 'Incoming Webhook Test'
            ]
        ]
    ];

    /**
     * Slack event URL.
     *
     * @var string
     */
    protected static $slackUrl = 'https://hooks.slack.com/services//T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX';

    /**
     * The application.
     *
     * @var \Illuminate\Container\Container
     */
    private $app;

    /**
     * Create Illuminate application
     *
     * @return \Illuminate\Container\Container
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function createApplication(): IlluminateContainer
    {
        if ($this->app !== null) {
            return $this->app;
        }

        // Create a new container
        $app = new IlluminateContainer();

        // Bind container itself
        $app->bind(IlluminateContainerContract::class, static function () use ($app) {
            return $app;
        });

        // Bind event dispatcher
        $app->bind(IlluminateDispatcherContract::class, static function () use ($app) {
            return new IlluminateDispatcher($app);
        });

        return $this->app = $app;
    }
}
