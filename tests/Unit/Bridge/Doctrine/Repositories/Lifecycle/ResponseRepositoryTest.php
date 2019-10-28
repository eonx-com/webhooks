<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Repositories\Lifecycle;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\WebhookResponseRepositoryInterface;
use EoneoPay\Webhooks\Models\WebhookResponseInterface;
use Tests\EoneoPay\Webhooks\DoctrineTestCase;
use Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Repositories\DataProvider\WebhookRequestData;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\FillableRepository
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Lifecycle\ResponseRepository
 */
class ResponseRepositoryTest extends DoctrineTestCase
{
    /**
     * Tests that getFillIterable returns expected data.
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testGetFillIterable(): void
    {
        $requestData = new WebhookRequestData($this->getEntityManager());
        $requestData
            ->createRequest(new DateTime('2019-10-10 12:00:00'), 1)
            ->createRequest(new DateTime('2019-10-11 12:00:00'), 2)
            ->createResponse(1, 200)
            ->createResponse(2, 200)
            ->build();

        $expectedResponses = $requestData->getResponses();

        $repository = $this->getRepository();

        $iterable = $repository->getFillIterable();
        $responses = \iterator_to_array($iterable);

        self::assertCount(2, $responses);
        self::assertSame($expectedResponses[1], $responses[0]);
        self::assertSame($expectedResponses[2], $responses[1]);
    }

    /**
     * Get repository.
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Lifecycle\ResponseRepository
     */
    private function getRepository(): WebhookResponseRepositoryInterface
    {
        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Lifecycle\ResponseRepository $repository
         */
        $repository = $this->getEntityManager()->getRepository(WebhookResponseInterface::class);

        return $repository;
    }
}
