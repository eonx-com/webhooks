<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Webhooks;

use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RetryProcessorInterface;
use EoneoPay\Webhooks\Webhooks\RetryProcessor;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Repositories\WebhookRequestRepositoryStub;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\EntityManagerStub;
use Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Entities\BaseEntityTestCase;

/**
 * @covers \EoneoPay\Webhooks\Webhooks\RetryProcessor
 */
class RetryProcessorTest extends BaseEntityTestCase
{
    /**
     * Test retry method adds list of provided failed request to queue
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testRetryMethod(): void
    {
        $webhookRequest1 = $this->getRequestEntity(null, 1);
        $webhookRequest2 = $this->getRequestEntity(null, 20);
        $webhookRequest3 = $this->getRequestEntity(null, 34);

        $repositories = [
            WebhookRequest::class =>
                new WebhookRequestRepositoryStub([$webhookRequest1, $webhookRequest2, $webhookRequest3])
        ];

        $entityManager = new EntityManagerStub(null, null, $repositories);
        $eventDispatcher = new EventDispatcherStub();

        $expectedRequests = [1, 20, 34];

        $processor = $this->getProcessor($entityManager, $eventDispatcher);

        $processor->retry('P1D');

        // assert event was dispatched the number of times as number of entities found
        self::assertCount(3, $eventDispatcher->getWebhooksRetried());
        self::assertSame($expectedRequests, $eventDispatcher->getWebhooksRetried());
    }

    /**
     * Get instance of retry processor
     *
     * @param \Doctrine\ORM\EntityManagerInterface|null $entityManager
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface|null $eventDispatcher
     *
     * @return \EoneoPay\Webhooks\Webhooks\Interfaces\RetryProcessorInterface
     */
    private function getProcessor(
        ?EntityManagerInterface $entityManager = null,
        ?EventDispatcherInterface $eventDispatcher = null
    ): RetryProcessorInterface {
        return new RetryProcessor(
            $entityManager ?? new EntityManagerStub(),
            $eventDispatcher ?? new EventDispatcherStub()
        );
    }
}
