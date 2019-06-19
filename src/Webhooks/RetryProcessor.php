<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Utils\DateInterval;
use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;

class RetryProcessor
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * RetryProcessor constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface $eventDispatcher
     */
    public function __construct
    (
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Loops through failed requests since date interval and pushes them to queue for re processing
     *
     * @param string $dateInterval Interval to go back into to find failed requests
     *
     * @return void
     *
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeStringException
     * @throws \EoneoPay\Utils\Exceptions\InvalidDateTimeIntervalException
     */
    public function retry(string $dateInterval): void
    {
        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\WebhookRequestRepository $repository
         */
        $repository = $this->entityManager->getRepository(WebhookRequest::class);

        $date = new DateTime();
        $date->sub(new DateInterval($dateInterval));
        $iterableRequests = $repository->getFailedRequests($date);

        foreach ($iterableRequests as $request) {
            $this->eventDispatcher->dispatchRequestRetry($request->getRequestId());
        }
    }
}