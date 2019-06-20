<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Repositories;

use ArrayIterator;
use DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\WebhookRequestRepositoryInterface;
use Iterator;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\RepositoryStub;

/**
 * @coversNothing
 */
class WebhookRequestRepositoryStub extends RepositoryStub implements WebhookRequestRepositoryInterface
{
    /**
     * WebhookRequestRepositoryStub constructor.
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest[]|null $requests
     */
    public function __construct(?array $requests = null)
    {
        parent::__construct($requests);
    }

    /**
     * {@inheritdoc}
     */
    public function getFailedRequestIds(DateTime $since): Iterator
    {
        $requestIds = [];
        $entities = $this->findAll();

        /**
         * Entities will be array of webhook requests as hinted in constructor
         *
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest $entity
         */
        foreach ($entities as $key => $entity) {
            $requestIds[] = [$key => ['requestId' => $entity->getRequestId()]];
        }

        return new ArrayIterator($requestIds);
    }
}
