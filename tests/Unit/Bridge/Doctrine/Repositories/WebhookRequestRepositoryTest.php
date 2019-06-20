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
     * Test get failed requests with no data
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function testGetFailedRequestsWithNoData(): void
    {
        $repository = $this->getRepository();
        $iterator = $repository->getFailedRequests(new DateTime('now'));

        self::assertFalse($iterator->valid());
    }

    /**
     * Test get failed requests with data
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testGetFailedRequestWithData(): void
    {
        $repository = $this->getRepository();

        $request = $this->getRequestEntity();

        $this->getEntityManager()->persist($request);
        $this->getEntityManager()->flush();
    }

    /**
     * Get repository
     *
     * @return \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\WebhookRequestRepository
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getRepository(): WebhookRequestRepository
    {
        $repository = $this->getEntityManager()->getRepository(WebhookRequest::class);

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\WebhookRequestRepository $repository
         */
        return $repository;
    }
}