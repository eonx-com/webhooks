<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Repositories;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\WebhookRequestRepository;
use Tests\EoneoPay\Webhooks\DoctrineTestCase;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\WebhookRequestRepository
 */
class WebhookRequestRepositoryTest extends DoctrineTestCase
{
    /**
     * Test get failed requests with data
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testGetFailedRequestWithData(): void
    {
        $entityManager = $this->getEntityManager();
        $repository = $this->getRepository();
        $activity = $this->getActivityEntity();
        $request1 = $this->getRequestEntity($activity, 1);
        $request2 = $this->getRequestEntity($activity, 2);
        $request3 = $this->getRequestEntity($activity, 3);
        $request4 = $this->getRequestEntity($activity, 4);
        $request5 = $this->getRequestEntity($activity, 5);

        $entityManager->persist($activity);
        $entityManager->persist($request1);
        $entityManager->persist($request2);
        $entityManager->persist($request3);
        $entityManager->persist($request4);
        $entityManager->persist($request5);

        // group of responses for request1 and none of them passed
        $entityManager->persist($this->getResponseEntity($request1, 1, 500));
        $entityManager->persist($this->getResponseEntity($request1, 2, 500));
        $entityManager->persist($this->getResponseEntity($request1, 3, 500));
        $entityManager->persist($this->getResponseEntity($request1, 4, 500));

        // group of responses for request2, with a successful response
        $entityManager->persist($this->getResponseEntity($request2, 5, 500));
        $entityManager->persist($this->getResponseEntity($request2, 6, 500));
        $entityManager->persist($this->getResponseEntity($request2, 7, 200));

        // group of responses for request3 and none of them passed
        $entityManager->persist($this->getResponseEntity($request3, 8, 500));
        $entityManager->persist($this->getResponseEntity($request3, 9, 500));
        $entityManager->persist($this->getResponseEntity($request3, 10, 404));

        $this->getEntityManager()->flush();

        $expected = [
            [0 => ['requestId' => '1']],
            [1 => ['requestId' => '3']],
            [2 => ['requestId' => '4']],
            [3 => ['requestId' => '5']]
        ];

        $resultsIterator = $repository->getFailedRequestIds(new DateTime());

        self::assertSame($expected, \iterator_to_array($resultsIterator));
    }

    /**
     * Test get failed requests with no data
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testGetFailedRequestsWithNoData(): void
    {
        $repository = $this->getRepository();
        $iterator = $repository->getFailedRequestIds(new DateTime('now'));

        self::assertFalse($iterator->valid());
    }

    /**
     * Get repository
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\WebhookRequestRepository
     */
    private function getRepository(): WebhookRequestRepository
    {
        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\WebhookRequestRepository $repository
         */
        $repository = $this->getEntityManager()->getRepository(WebhookRequest::class);

        return $repository;
    }
}
