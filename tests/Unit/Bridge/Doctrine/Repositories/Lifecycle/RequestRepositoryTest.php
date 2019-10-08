<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Repositories\Lifecycle;

use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\WebhookRequestRepositoryInterface;
use EoneoPay\Webhooks\Models\WebhookRequestInterface;
use Tests\EoneoPay\Webhooks\DoctrineTestCase;
use Tests\EoneoPay\Webhooks\Stubs\Externals\EntityStub;
use Tests\EoneoPay\Webhooks\TestCases\Traits\ModelFactoryTrait;
use Tests\EoneoPay\Webhooks\Unit\Bridge\Doctrine\Repositories\DataProvider\WebhookRequestData;

/**
 * @covers \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Lifecycle\RequestRepository
 */
class RequestRepositoryTest extends DoctrineTestCase
{
    use ModelFactoryTrait;

    /**
     * Test get failed requests with basic data.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testGetFailedRequest(): void
    {
        (new WebhookRequestData($this->getEntityManager()))
            ->createRequest(new DateTime('2019-10-10 12:00:00'), 1)
            ->createRequest(new DateTime('2019-10-11 12:00:00'), 2)
            ->createResponse(1, 500)
            ->createResponse(1, 404)
            ->createResponse(2, 500)
            ->createResponse(2, 500)
            ->build();

        $expected = [
            ['requestId' => '1'],
            ['requestId' => '2'],
        ];

        $repository = $this->getRepository();

        $resultsIterator = $repository->getFailedRequestIds(new DateTime('2019-10-01'));

        $results = [];
        \array_push($results, ...$resultsIterator);

        self::assertEquals($expected, $results);
    }

    /**
     * Test query obeys date filter and response status.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testGetFailedRequestsObeysDateFilter(): void
    {
        (new WebhookRequestData($this->getEntityManager()))
            ->createRequest(new DateTime('2019-10-10 12:00:00'), 1)
            ->createRequest(new DateTime('2019-10-11 12:00:00'), 2)
            ->createRequest(new DateTime('2019-10-12 15:00:00'), 3)
            ->createRequest(new DateTime('2019-10-13 13:00:00'), 4)
            ->createRequest(new DateTime('2019-10-14 15:00:00'), 5)
            ->createRequest(new DateTime('2019-10-15 11:00:00'), 6)
            ->createResponse(6, 200)
            ->build();

        $expected = [
            ['requestId' => '4'],
            ['requestId' => '5'],
        ];

        $repository = $this->getRepository();

        $findRequestsSince = new DateTime('2019-10-12 16:00:00');
        $resultsIterator = $repository
            ->getFailedRequestIds(
                $findRequestsSince
            );

        $results = [];
        \array_push($results, ...$resultsIterator);

        self::assertEquals($expected, $results);
    }

    /**
     * Test query omits requests that have already succeeded.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testGetFailedRequestsOmitsSucceededRequests(): void
    {
        (new WebhookRequestData($this->getEntityManager()))
            ->createRequest(new DateTime('2019-10-10 12:00:00'), 1)
            ->createRequest(new DateTime('2019-10-11 12:00:00'), 2)
            ->createRequest(new DateTime('2019-10-11 12:00:00'), 3)
            ->createRequest(new DateTime('2019-10-11 12:00:00'), 4)
            ->createResponse(1, 200)
            ->createResponse(2, 500)
            ->createResponse(2, 500)
            ->createResponse(3, 404)
            ->createResponse(3, 200)
            ->build();

        $expected = [
            ['requestId' => '2'],
            ['requestId' => '4'],
        ];

        $repository = $this->getRepository();

        $resultsIterator = $repository->getFailedRequestIds(new DateTime('2019-10-01'));

        $results = [];
        \array_push($results, ...$resultsIterator);

        self::assertEquals($expected, $results);
    }

    /**
     * Test that get latest activity payload for a given primary class and primary id will
     * return expected activity.
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \ReflectionException
     */
    public function testGetLatestActivityReturnsExpectedActivity(): void
    {
        //--------- other activity
        $differentActivity = $this->getActivityEntity();
        $differentActivity->setPrimaryClass('DifferentClass');
        $differentActivity->setPrimaryId('DifferentClassId');

        $this->getEntityManager()->persist($differentActivity);

        (new WebhookRequestData($this->getEntityManager(), $differentActivity))
            ->createRequest(new DateTime('2020-10-10 12:00:00'), 11)
            ->createRequest(new DateTime('2020-10-11 12:00:00'), 12)
            ->createResponse(11, 500)
            ->createResponse(12, 200)
            ->build();

        //--------- expected activity
        $expectedActivity = $this->getActivityEntity();

        $this->getEntityManager()->persist($expectedActivity);

        (new WebhookRequestData($this->getEntityManager(), $expectedActivity))
            ->createRequest(new DateTime('2020-10-10 12:00:00'), 1)
            ->createRequest(new DateTime('2020-10-11 12:00:00'), 2)
            ->createRequest(new DateTime('2020-10-11 12:00:00'), 3)
            ->createRequest(new DateTime('2020-10-11 12:00:00'), 4)
            ->createResponse(1, 200)
            ->createResponse(2, 500)
            ->createResponse(2, 500)
            ->createResponse(3, 404)
            ->createResponse(3, 200)
            ->build();

        $repository = $this->getRepository();

        $actualActivity = $repository->getLatestActivity(EntityStub::class, '55');

        self::assertSame($expectedActivity, $actualActivity);
    }

    /**
     * Test that get latest activity payload for a given primary class and primary id will
     * return null when no requests exists.
     *
     * @return void
     *
     * @runInSeparateProcess
     */
    public function testGetLatestActivityReturnsNull(): void
    {
        $repository = $this->getRepository();

        $actualActivity = $repository->getLatestActivity(EntityStub::class, '55');

        self::assertNull($actualActivity);
    }

    /**
     * Get repository.
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\WebhookRequestRepositoryInterface
     */
    private function getRepository(): WebhookRequestRepositoryInterface
    {
        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\WebhookRequestRepositoryInterface $repository
         */
        $repository = $this->getEntityManager()->getRepository(WebhookRequestInterface::class);

        return $repository;
    }
}
