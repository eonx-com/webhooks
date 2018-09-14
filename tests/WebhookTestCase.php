<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks;

use EoneoPay\Webhooks\Bridge\Laravel\Events\Http\JsonEvent;
use EoneoPay\Webhooks\Bridge\Laravel\Events\Http\XmlEvent;
use EoneoPay\Webhooks\Events\Interfaces\EventInterface;
use Illuminate\Bus\Dispatcher as IlluminateJobDispatcher;
use Illuminate\Container\Container as IlluminateContainer;
use Illuminate\Contracts\Container\Container as IlluminateContainerContract;
use Illuminate\Contracts\Events\Dispatcher as IlluminateDispatcherContract;
use Illuminate\Events\Dispatcher as IlluminateDispatcher;
use Mockery;

/**
 * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
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


    /** @noinspection ReturnTypeCanBeDeclaredInspection Application is nothing else than container */

    /**
     * Get Slack webhook event.
     *
     * @return \EoneoPay\Webhooks\Events\Interfaces\EventInterface
     */
    final protected static function getSlackEvent(): EventInterface
    {
        return new JsonEvent(self::$slackUrl, 'POST', self::$slackPayload, []);
    }

    /**
     * Get XML webhook event.
     *
     * @return \EoneoPay\Webhooks\Events\Interfaces\EventInterface
     */
    final protected static function getXmlEvent(): EventInterface
    {
        return new XmlEvent(self::$httpUrl, 'POST', self::$httpPayload, []);
    }

    /**
     * Specify list of jobs expected to be mocked.
     *
     * @param mixed[]|string $jobs Jobs
     *
     * @return self
     *
     * @see https://laravel.com/api/5.5/Illuminate/Foundation/Testing/TestCase.html#method_expectsJobs
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
     * Get Illuminate application.
     *
     * @return \Illuminate\Container\Container
     */
    protected function getApplication(): IlluminateContainer
    {
        if ($this->app !== null) {
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
}
