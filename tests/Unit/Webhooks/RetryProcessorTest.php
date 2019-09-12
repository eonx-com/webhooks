<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Webhooks;

use EoneoPay\Externals\ORM\Interfaces\EntityManagerInterface;
use EoneoPay\Utils\DateInterval;
use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RetryProcessorInterface;
use EoneoPay\Webhooks\Webhooks\RetryProcessor;
use Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Repositories\WebhookRequestRepositoryStub;
use Tests\EoneoPay\Webhooks\Stubs\Event\EventDispatcherStub;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\ExternalEntityManagerStub;
use Tests\EoneoPay\Webhooks\TestCase;
use Tests\EoneoPay\Webhooks\TestCases\Traits\ModelFactoryTrait;

/**
 * @covers \EoneoPay\Webhooks\Webhooks\RetryProcessor
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) Requires many classes for full testing
 */
class RetryProcessorTest extends TestCase
{
    use ModelFactoryTrait;

    /**
     * Test retry method adds list of provided failed request to queue
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function testRetryMethod(): void
    {
        $webhookRequest1 = $this->getRequestEntity(null, null, 1);
        $webhookRequest2 = $this->getRequestEntity(null, null, 20);
        $webhookRequest3 = $this->getRequestEntity(null, null, 34);

        $repositoryStub = new WebhookRequestRepositoryStub(null, [
            $webhookRequest1,
            $webhookRequest2,
            $webhookRequest3
        ]);
        $repositories = [WebhookRequest::class => $repositoryStub];

        $entityManager = new ExternalEntityManagerStub($repositories);
        $eventDispatcher = new EventDispatcherStub();

        $expectedRequests = [1, 20, 34];
        $expectedSinceDate = new DateTime('-1 day');

        $processor = $this->getProcessor($entityManager, $eventDispatcher);
        $processor->retry(new DateInterval('P1D'));

        // assert event was dispatched the number of times as number of entities found
        self::assertCount(3, $eventDispatcher->getWebhooksRetried());
        self::assertSame($expectedRequests, $eventDispatcher->getWebhooksRetried());
        // assert the since date provided to repository to look for requests since is within expected range
        self::assertEqualsWithDelta($expectedSinceDate, $repositoryStub->getSince(), 10);
    }

    /**
     * Get instance of retry processor
     *
     * @param \EoneoPay\Externals\ORM\Interfaces\EntityManagerInterface|null $entityManager
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface|null $eventDispatcher
     *
     * @return \EoneoPay\Webhooks\Webhooks\Interfaces\RetryProcessorInterface
     */
    private function getProcessor(
        ?EntityManagerInterface $entityManager = null,
        ?EventDispatcherInterface $eventDispatcher = null
    ): RetryProcessorInterface {
        return new RetryProcessor(
            $entityManager ?? new ExternalEntityManagerStub(),
            $eventDispatcher ?? new EventDispatcherStub()
        );
    }
}
