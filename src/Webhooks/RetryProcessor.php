<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Webhooks;

use EoneoPay\Externals\ORM\Interfaces\EntityManagerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest;
use EoneoPay\Webhooks\Events\Interfaces\EventDispatcherInterface;

class RetryProcessor
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
    public function __construct
    (
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function retry(): void
    {
        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Repositories\WebhookRequestRepository $repository
         */
        $repository = $this->entityManager->getRepository(WebhookRequest::class);

        $iterableRequests = $repository->getPassedRequests()->iterate();

        foreach ($iterableRequests as $request) {
            $this->eventDispatcher->dispatchRequestRetry($request->getRequestId());
        }
    }
}