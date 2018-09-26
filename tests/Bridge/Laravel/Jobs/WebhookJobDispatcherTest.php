<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Bridge\Laravel\Jobs;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Externals\HttpClient\Response;
use EoneoPay\Webhooks\Bridge\Laravel\Jobs\WebhookJob;
use EoneoPay\Webhooks\Bridge\Laravel\Jobs\WebhookJobDispatcher;
use EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobInterface;
use Illuminate\Bus\Dispatcher as IlluminateJobDispatcher;
use Mockery;
use Tests\EoneoPay\Webhooks\WebhookTestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Jobs\WebhookJob
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Jobs\WebhookJobDispatcher
 *
 * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
 */
class WebhookJobDispatcherTest extends WebhookTestCase
{
    /**
     * @var \EoneoPay\Webhooks\Bridge\Laravel\Jobs\WebhookJobDispatcher
     */
    private $jobDispatcher;

    /**
     * Test dispatch json event job.
     *
     * @return void
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testDispatchJsonEventJob(): void
    {
        $mockHttpClient = Mockery::mock(ClientInterface::class);

        $data = self::getSlackEvent()->serialize();

        $mockHttpClient->shouldReceive('request')
            ->with('POST', self::getSlackEvent()->getUrl(), $data)
            ->andReturn(new Response([], 200));

        $this->getApplication()->instance(
            ClientInterface::class,
            $mockHttpClient
        );

        $job = new WebhookJob(
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
     * Test dispatch xml event job.
     *
     * @return void
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function testDispatchXmlEventJob(): void
    {
        $mockHttpClient = Mockery::mock(ClientInterface::class);

        $data = self::getXmlEvent()->serialize();

        $mockHttpClient->shouldReceive('request')
            ->with('POST', self::getXmlEvent()->getUrl(), $data)
            ->andReturn(new Response([], 200));

        $this->getApplication()->instance(
            ClientInterface::class,
            $mockHttpClient
        );

        $job = new WebhookJob(
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
     * Setup.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // construct Webhook job dispatcher
        $this->jobDispatcher = new WebhookJobDispatcher(
            new IlluminateJobDispatcher(
                $this->getApplication()
            )
        );
    }
}
