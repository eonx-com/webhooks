<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhook\Bridge\Laravel\Jobs;

use EoneoPay\External\HttpClient\Interfaces\ClientInterface;
use EoneoPay\External\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\External\HttpClient\Response;
use EoneoPay\Webhook\Bridge\Laravel\Jobs\WebhookJob;
use EoneoPay\Webhook\Bridge\Laravel\Jobs\WebhookJobDispatcher;
use EoneoPay\Webhook\Jobs\Interfaces\WebhookJobInterface;
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

        $postData = [
            'auth' => [
                self::getSlackEvent()->getUsername(),
                self::getSlackEvent()->getPassword(),
                self::getSlackEvent()->getAuthType()
            ],
            'body' => self::getSlackEvent()->getPayload()->serialize()
        ];

        $mockHttpClient->shouldReceive('request')
            ->with('POST', self::getSlackEvent()->getUrl(), $postData)
            ->andReturn(new Response([], 200));

        $this->getApplication()->instance(
            ClientInterface::class,
            $mockHttpClient
        );

        $job = new WebhookJob(
            $this->getApplication()->get(ClientInterface::class),
            self::getSlackEvent()
        );

        $this->expectsJobs(WebhookJobInterface::class);

        $response = $this->jobDispatcher->dispatch($job);

        // assertions
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertTrue($response->isSuccessful());
        self::assertEquals('200', $response->getStatusCode());
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
    public function testDispatchXmlEventJob(): void
    {
        $mockHttpClient = Mockery::mock(ClientInterface::class);

        $postData = [
            'auth' => [
                self::getXmlEvent()->getUsername(),
                self::getXmlEvent()->getPassword(),
                self::getXmlEvent()->getAuthType()
            ],
            'body' => self::getXmlEvent()->getPayload()->serialize()
        ];

        $mockHttpClient->shouldReceive('request')
            ->with('POST', self::getXmlEvent()->getUrl(), $postData)
            ->andReturn(new Response([], 200));

        $this->getApplication()->instance(
            ClientInterface::class,
            $mockHttpClient
        );

        $job = new WebhookJob(
            $this->getApplication()->get(ClientInterface::class),
            self::getXmlEvent()
        );

        $this->expectsJobs(WebhookJobInterface::class);

        $response = $this->jobDispatcher->dispatch($job);

        // assertions
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertTrue($response->isSuccessful());
        self::assertEquals('200', $response->getStatusCode());
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
    public function testDispatchJsonEventJob(): void
    {
        $mockHttpClient = Mockery::mock(ClientInterface::class);

        $postData = [
            'auth' => [
                self::getHttpEvent()->getUsername(),
                self::getHttpEvent()->getPassword(),
                self::getHttpEvent()->getAuthType()
            ],
            'body' => self::getHttpEvent()->getPayload()->serialize()
        ];

        $mockHttpClient->shouldReceive('request')
            ->with('POST', self::getHttpEvent()->getUrl(), $postData)
            ->andReturn(new Response([], 200));

        $this->getApplication()->instance(
            ClientInterface::class,
            $mockHttpClient
        );

        $job = new WebhookJob(
            $this->getApplication()->get(ClientInterface::class),
            self::getHttpEvent()
        );

        $this->expectsJobs(WebhookJobInterface::class);

        $response = $this->jobDispatcher->dispatch($job);

        // assertions
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertTrue($response->isSuccessful());
        self::assertEquals('200', $response->getStatusCode());
    }
}
