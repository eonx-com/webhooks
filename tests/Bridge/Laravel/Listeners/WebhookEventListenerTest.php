<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Bridge\Laravel\Listeners;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener;
use EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface;
use EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobInterface;
use Mockery;
use Tests\EoneoPay\Webhooks\WebhookTestCase;

class WebhookEventListenerTest extends WebhookTestCase
{
    /** @var  \EoneoPay\Externals\HttpClient\Interfaces\ClientInterface */
    private $mockClient;

    /** @var \EoneoPay\Webhooks\Jobs\Interfaces\WebhookJobDispatcherInterface */
    private $mockDispatcher;

    /** @var \EoneoPay\Webhooks\Bridge\Laravel\Listeners\WebhookEventListener */
    private $webhookEventListener;

    /**
     * Setup.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->mockClient = Mockery::mock(ClientInterface::class);
        $this->mockDispatcher = Mockery::mock(WebhookJobDispatcherInterface::class);
    }

    /**
     * Test handle webhook http event.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
     */
    public function testHandleHttpEvent(): void
    {
        $this->mockDispatcher->shouldReceive('dispatch')->andReturn(['ok'])
            ->with(Mockery::type(WebhookJobInterface::class));

        $this->webhookEventListener = new WebhookEventListener(
            $this->mockClient,
            $this->mockDispatcher
        );

        self::assertNotNull($this->webhookEventListener);
        self::assertNotEmpty($this->webhookEventListener->handle(self::getHttpEvent()));
        self::assertInternalType('array', $this->webhookEventListener->handle(self::getHttpEvent()));
    }

    /**
     * Test handle webhook xml event.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
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
     * Test handle webhook Slack event.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.StaticAccess) Inherited from Mockery
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
}
