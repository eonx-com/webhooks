<?php declare(strict_types=1);

namespace Tests\EoneoPay\Webhook;

use EoneoPay\Webhook\Bridge\Laravel\Events\WebhookHttpEvent;
use EoneoPay\Webhook\Bridge\Laravel\Events\WebhookSlackEvent;
use Illuminate\Bus\Dispatcher as IlluminateJobDispatcher;
use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Contracts\Container\Container as IlluminateContainerContract;
use Illuminate\Contracts\Events\Dispatcher as IlluminateDispatcherContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Events\Dispatcher as IlluminateDispatcher;
use Mockery;

abstract class WebhookTestCase extends TestCase
{
    /**
     * Http event payload.
     *
     * @var array
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
     * Slack event payload.
     *
     * @var array
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
     * Slack event URL.
     *
     * @var string
     */
    protected static $slackUrl = 'https://hooks.slack.com/services//T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX';

    /**
     * The applcation.
     *
     * @var Application
     */
    private $app;


    /** @noinspection ReturnTypeCanBeDeclaredInspection Application is nothing else than container */

    /**
     * Get Illuminate application.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    protected function getApplication()
    {
        if (null !== $this->app) {
            return $this->app;
        }

        // Create a new container
        $app = new IlluminateContainer();

        // Bind container itself
        $app->bind(
            IlluminateContainerContract::class,
            function () use ($app) {
                return $app;
            }
        );

        // Bind event dispatcher
        $app->bind(
            IlluminateDispatcherContract::class,
            function () use ($app) {
                return new IlluminateDispatcher($app);
            }
        );

        $this->app = $app;

        return $this->app;
    }

    /**
     * Specify list of jobs expected to be mocked.
     *
     * @param array|string $jobs Jobs
     *
     * @return self
     *
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
     * @see                                  https://laravel.com/api/5.5/Illuminate/Foundation/Testing/TestCase.html#method_expectsJobs
     */
    protected function expectsJobs($jobs): self
    {
        $jobs = \is_array($jobs) ? $jobs : \func_get_args();

        $mock = Mockery::mock('Illuminate\Bus\Dispatcher[dispatch]', [$this->app]);

        foreach ($jobs as $job) {
            $mock->shouldReceive('dispatch')->atLeast()->once()
                ->with(Mockery::type($job));
        }

        $this->app->instance(
            IlluminateJobDispatcher::class,
            $mock
        );

        return $this;
    }


    /**
     * Get webhook Slack event object.
     *
     * @return WebhookSlackEvent
     */
    final protected static function getSlackEvent(): WebhookSlackEvent
    {
        return new WebhookSlackEvent([
            'url' => self::$slackUrl,
            'payload' => self::$slackPayload
        ]);
    }

    /**
     * Get webhook http event object.
     *
     * @return WebhookHttpEvent
     */
    final protected static function getHttpEvent(): WebhookHttpEvent
    {
        return new WebhookHttpEvent([
            'url' => self::$httpUrl,
            'payload' => self::$httpPayload
        ]);
    }
}
