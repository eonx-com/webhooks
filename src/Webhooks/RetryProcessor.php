<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Utils\DateInterval;
use EoneoPay\Utils\DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;
use EoneoPay\Webhooks\Webhooks\Interfaces\RetryProcessorInterface;

class RetryProcessor implements RetryProcessorInterface
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
        $iterableRequests = $repository->getFailedRequestIds($date);

        foreach($iterableRequests as $key => $request){
            $this->eventDispatcher->dispatchRequestRetry((int)$request[$key]['requestId']);
        }
    }
}
