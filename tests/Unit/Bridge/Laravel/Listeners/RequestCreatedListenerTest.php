<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Laravel\Listeners;

use EoneoPay\Webhooks\Bridge\Laravel\Events\WebhookRequestCreatedEvent;
use EoneoPay\Webhooks\Bridge\Laravel\Listeners\RequestCreatedListener;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Entities\Webhooks\RequestStub;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Handlers\RequestHandlerStub;
use Tests\EoneoPay\Webhooks\Stubs\Webhooks\RequestProcessorStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Laravel\Listeners\RequestCreatedListener
 */
class RequestCreatedListenerTest extends TestCase
{
    /**
     * Tests handle.
     *
     * @return void
     */
    public function testHandle(): void
    {
        $request = new RequestStub(5);

        $handler = new RequestHandlerStub();
        $handler->setNextRequest($request);
        $processor = new RequestProcessorStub();

        $listener = new RequestCreatedListener($handler, $processor);
        $listener->handle(new WebhookRequestCreatedEvent(5));

        self::assertSame([$request], $processor->getProcessed());
    }
}
