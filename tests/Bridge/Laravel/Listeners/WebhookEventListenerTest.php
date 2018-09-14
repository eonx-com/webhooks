<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Bridge\Laravel\Listeners;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener;
use EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface;
use EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobInterface;
use Mockery;
use Tests\EoneoPay\Webhooks\WebhookTestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener
 *
 * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
 */
class WebhookEventListenerTest extends WebhookTestCase
{
    /**
     * @var  \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface
     */
    private $mockClient;

    /**
     * @var \EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface
     */
    private $mockDispatcher;

    /**
     * @var \EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener
     */
    private $webhookEventListener;

    /**
     * Test handle webhook Slack event.
     *
     * @return void
     */
    public function testHandleSlackEvent(): void
    {
        $this->mockDispatcher->shouldReceive('dispatch')->andReturn(['ok'])
            ->with(Mockery::type(WebhookJobInterface::class));

        $this->webhookEventListener = new WebhookEventListener(
            $this->mockClient,
            $this->mockDispatcher
        );

        self::assertNotNull($this->webhookEventListener);
        self::assertNotEmpty($this->webhookEventListener->handle(self::getSlackEvent()));
        self::assertInternalType('array', $this->webhookEventListener->handle(self::getSlackEvent()));
    }

    /**
     * Test handle webhook xml event.
     *
     * @return void
     */
    public function testHandleXmlEvent(): void
    {
        $this->mockDispatcher->shouldReceive('dispatch')->andReturn(['ok'])
            ->with(Mockery::type(WebhookJobInterface::class));

        $this->webhookEventListener = new WebhookEventListener(
            $this->mockClient,
            $this->mockDispatcher
        );

        self::assertNotNull($this->webhookEventListener);
        self::assertNotEmpty($this->webhookEventListener->handle(self::getXmlEvent()));
        self::assertInternalType('array', $this->webhookEventListener->handle(self::getXmlEvent()));
    }

    /**
     * Setup: Mock http client and event dispatcher.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = Mockery::mock(ClientInterface::class);
        $this->mockDispatcher = Mockery::mock(WebhookJobDispatcherInterface::class);
    }
}
