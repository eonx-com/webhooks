<?php declare(strict_types=1);

namespace Tests\EoneoPay\Webhook\Bridge\Laravel\Jobs;

use EoneoPay\External\HttpClient\Interfaces\ClientInterface;
use EoneoPay\External\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\External\HttpClient\Response;
use EoneoPay\Webhook\Bridge\Laravel\Jobs\WebhookHttpEventJob;
use EoneoPay\Webhook\Bridge\Laravel\Jobs\WebhookJobDispatcher;
use EoneoPay\Webhook\Bridge\Laravel\Jobs\WebhookSlackEventJob;
use Illuminate\Bus\Dispatcher as IlluminateJobDispatcher;
use Mockery;
use Tests\EoneoPay\Webhook\WebhookTestCase;

class WebhookJobDispatcherTest extends WebhookTestCase
{
    /** @var WebhookJobDispatcher */
    private $jobDispatcher;

    /**
     * Setup.
     *
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
     */
    protected function setUp()
    {
        parent::setUp();

        // construct Webhook job dispatcher
        $this->jobDispatcher = new WebhookJobDispatcher(
            new IlluminateJobDispatcher(
                $this->getApplication()
            )
        );
    }

    /**
     * Test dispatch Slack event job.
     *
     * @return void
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
     */
    public function testDispatchSlackEventJob(): void
    {
        $mockHttpClient = Mockery::mock(ClientInterface::class);

        $mockHttpClient->shouldReceive('request')
            ->once()
            ->with('POST', self::getSlackEvent()->get('url'), [
                'json' => self::getSlackEvent()->get('payload')
            ])
            ->andReturn(new Response([], 200));

        $this->getApplication()->instance(
            ClientInterface::class,
            $mockHttpClient
        );

        $job = new WebhookSlackEventJob(
            $this->getApplication()->get(ClientInterface::class),
            self::getSlackEvent()
        );

        $this->expectsJobs(WebhookSlackEventJob::class);

        $response = $this->jobDispatcher->dispatch($job);

        // assertions
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertTrue($response->isSuccessful());
        self::assertEquals('200', $response->getStatusCode());
    }

    /**
     * Test dispatch http event job.
     *
     * @return void
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
     */
    public function testDispatchHttpEventJob(): void
    {
        $mockHttpClient = Mockery::mock(ClientInterface::class);

        $mockHttpClient->shouldReceive('request')
            ->once()
            ->with('POST', self::getHttpEvent()->get('url'), [
                'auth' => [
                    self::getHttpEvent()->get('username'),
                    self::getHttpEvent()->get('password'),
                    self::getHttpEvent()->get('auth_type')
                ],
                'json' => self::getHttpEvent()->get('payload')
            ])
            ->andReturn(new Response([], 200));

        $this->getApplication()->instance(
            ClientInterface::class,
            $mockHttpClient
        );

        $job = new WebhookHttpEventJob(
            $this->getApplication()->get(ClientInterface::class),
            self::getHttpEvent()
        );

        $response = $this->jobDispatcher->dispatch($job);

        // assertions
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertTrue($response->isSuccessful());
        self::assertEquals('200', $response->getStatusCode());
    }
}
