<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Listeners;

use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestRetryEvent;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\RequestRetryListener;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entity\WebhookRequestStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\RequestHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Webhooks\RequestProcessorStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Listeners\RequestRetryListener
 */
class RequestRetryListenerTest extends TestCase
{
    /**
     * Tests handle.
     *
     * @return void
     */
    public function testHandle(): void
    {
        $request = new WebhookRequestStub(5);

        $handler = new RequestHandlerStub();
        $handler->setNextRequest($request);
        $processor = new RequestProcessorStub();

        $listener = new RequestRetryListener($handler, $processor);
        $listener->handle(new WebhookRequestRetryEvent(5));

        static::assertSame([$request], $processor->getProcessed());
    }
}
