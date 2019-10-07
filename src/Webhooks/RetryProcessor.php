<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use EoneoPay\Externals\ORM\Interfaces\EntityManagerInterface;
use EoneoPay\Utils\DateInterval;
use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Webhooks\Request;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RetryProcessorInterface;

class RetryProcessor implements RetryProcessorInterface
{
    /**
     * @var \EoneoPay\Externals\ORM\Interfaces\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * RetryProcessor constructor.
     *
     * @param \EoneoPay\Externals\ORM\Interfaces\EntityManagerInterface $entityManager
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     */
    public function retry(DateInterval $dateInterval): void
    {
        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Webhooks\RequestRepository $repository
         */
        $repository = $this->entityManager->getRepository(Request::class);

        $date = new DateTime();
        $date->sub($dateInterval);
        $iterableRequests = $repository->getFailedRequestIds($date);

        foreach ($iterableRequests as $request) {
            $this->eventDispatcher->dispatchRequestRetry((int)$request['requestId']);
        }
    }
}
