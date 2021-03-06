<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Events;

use EoneoPay\Externals\Logger\Interfaces\LoggerInterface;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Events\LoggerAwareEventDispatcher;
use Tests\EoneoPay\Webhooks\Stubs\Events\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\Externals\LoggerStub;
use Tests\EoneoPay\Webhooks\TestCase;

/**
 * @covers \EoneoPay\Webhooks\Events\LoggerAwareEventDispatcher
 */
class LoggerAwareEventDispatcherTest extends TestCase
{
    /**
     * Returns the instance under test.
     *
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface $dispatcher
     * @param \EoneoPay\Externals\Logger\Interfaces\LoggerInterface $logger
     *
     * @return \EoneoPay\Webhooks\Events\LoggerAwareEventDispatcher
     */
    public function getDispatcher(
        EventDispatcherInterface $dispatcher,
        LoggerInterface $logger
    ): LoggerAwareEventDispatcher {
        return new LoggerAwareEventDispatcher($dispatcher, $logger);
    }

    /**
     * Tests activityCreated.
     *
     * @return void
     */
    public function testActivityCreated(): void
    {
        $innerDispatcher = new EventDispatcherStub();
        $logger = new LoggerStub();

        $expectedLogs = [
            [
                'message' => 'Activity Created',
                'context' => [
                    'activity_id' => 1,
                ],
            ],
        ];

        $dispatcher = $this->getDispatcher($innerDispatcher, $logger);

        $dispatcher->dispatchActivityCreated(1);

        self::assertSame([1], $innerDispatcher->getActivityCreated());
        self::assertSame($expectedLogs, $logger->getLogs());
    }

    /**
     * Tests webhook request.
     *
     * @return void
     */
    public function testWebhookRequest(): void
    {
        $innerDispatcher = new EventDispatcherStub();
        $logger = new LoggerStub();

        $expectedLogs = [
            [
                'message' => 'Webhook Request Created',
                'context' => [
                    'request_id' => 1,
                ],
            ],
        ];

        $dispatcher = $this->getDispatcher($innerDispatcher, $logger);

        $dispatcher->dispatchRequestCreated(1);

        self::assertSame([1], $innerDispatcher->getWebhooksRequested());
        self::assertSame($expectedLogs, $logger->getLogs());
    }

    /**
     * Tests webhook request retry also adds a log.
     *
     * @return void
     */
    public function testWebhookRequestRetryDispatcherLogs(): void
    {
        $innerDispatcher = new EventDispatcherStub();
        $logger = new LoggerStub();

        $expectedLogs = [
            [
                'message' => 'Webhook Failed Request Retried',
                'context' => [
                    'request_id' => 1,
                ],
            ],
        ];

        $dispatcher = $this->getDispatcher($innerDispatcher, $logger);

        $dispatcher->dispatchRequestRetry(1);

        self::assertSame([1], $innerDispatcher->getWebhooksRetried());
        self::assertSame($expectedLogs, $logger->getLogs());
    }
}
