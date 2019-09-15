<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Bridge\Doctrine\Repositories;

use ArrayIterator;
use DateTime;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity;
use EoneoPay\Webhooks\Bridge\Doctrine\Repositories\Interfaces\WebhookRequestRepositoryInterface;
use Tests\EoneoPay\Webhooks\Stubs\Vendor\Doctrine\ORM\RepositoryStub;

/**
 * @coversNothing
 */
class WebhookRequestRepositoryStub extends RepositoryStub implements WebhookRequestRepositoryInterface
{
    /**
     * Activity from latest webhook request.
     *
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity|null
     */
    private $latestActivity;

    /**
     * Since date time set by getFailedRequestIds
     *
     * @var \DateTime
     */
    private $since;

    /**
     * WebhookRequestRepositoryStub constructor.
     *
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entities\Activity|null $latestActivity
     * @param \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest[]|null $requests
     */
    public function __construct(
        ?Activity $latestActivity = null,
        ?array $requests = null
    ) {
        parent::__construct($requests);

        $this->latestActivity = $latestActivity;
    }

    /**
     * {@inheritdoc}
     */
    public function getFailedRequestIds(DateTime $since): iterable
    {
        $this->since = $since;
        $requestIds = [];
        $entities = $this->findAll();

        /**
         * Entities will be array of webhook requests as hinted in constructor
         *
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest $entity
         */
        foreach ($entities as $entity) {
            $requestIds[] = ['requestId' => $entity->getRequestId()];
        }

        return new ArrayIterator($requestIds);
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestActivity(string $primaryClass, string $primaryId): ?Activity
    {
        return $this->latestActivity;
    }

    /**
     * Get date time set by getFailedRequestIds method
     *
     * @return \DateTime
     */
    public function getSince(): DateTime
    {
        return $this->since;
    }
}
